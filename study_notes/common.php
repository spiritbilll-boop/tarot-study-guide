<?php
/*
============================================================
    study_notes_common.php
    Common functions for the Tarot Study Notes Manager
    Version 1.1
============================================================
*/
require_once __DIR__ . '/../common/db.php';
/*
============================================================
Get all Tarot cards
============================================================
*/
function get_cards(mysqli $conn)
{
    $cards = array();
    $sql = "
        SELECT
            id,
            card_name
        FROM
            tarot_cards
        ORDER BY
            id
    ";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc())
    {
        $cards[] = $row;
    }
    return $cards;
}
/*
============================================================
Return next sequence number
============================================================
*/
function get_next_sequence(mysqli $conn,
                           int $card_id,
                           string $orientation)
{
    $sql = "
        SELECT
            COALESCE(MAX(sequence_no),0)+1 AS next_seq
        FROM
            tarot_card_notes
        WHERE
            card_id=?
        AND
            orientation=?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "is",
        $card_id,
        $orientation
    );
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return intval($row['next_seq']);
}
/*
============================================================
get_notes()
Returns all Study Notes for one card and
orientation ordered by sequence number.
Parameters
$conn
$card_id
$orientation
Returns
Array of associative arrays.
============================================================
*/
function get_notes(
    mysqli $conn,
    int $card_id,
    string $orientation
)
{
    $notes = array();
$sql = "
    SELECT
        id,
        sequence_no,
        title,
        description,
        source,
        enabled,
        notes,
        date_added
    FROM
        tarot_card_notes
    WHERE
        card_id = ?
    AND
        orientation = ?
    ORDER BY
        sequence_no
";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
    {
        return $notes;
    }
    $stmt->bind_param(
        "is",
        $card_id,
        $orientation
    );
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc())
    {
        $notes[] = $row;
    }
    $stmt->close();
    return $notes;
}
/*
============================================================
Insert Study Note
============================================================
*/
function insert_study_note(
    mysqli $conn,
    int $card_id,
    string $orientation,
    string $title,
    string $description,
    string $source,
    string $notes,
    bool $enabled
)
{
    $sequence_no =
        get_next_sequence(
            $conn,
            $card_id,
            $orientation
        );
    $sql = "
        INSERT INTO tarot_card_notes
        (
            card_id,
            orientation,
            sequence_no,
            title,
            description,
            source,
            enabled,
            notes
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
    {
        return false;
    }
    $enabled_int = $enabled ? 1 : 0;
    $stmt->bind_param(
        "isisssis",
        $card_id,
        $orientation,
        $sequence_no,
        $title,
        $description,
        $source,
        $enabled_int,
        $notes
    );
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
/*
============================================================
Simple HTML escape helper
============================================================
*/
function h($text)
{
    return htmlspecialchars(
        $text,
        ENT_QUOTES,
        "UTF-8"
    );
}
function get_enabled_notes_for_card(
    mysqli $conn,
    int $card_id,
    string $orientation
)
{
    $sql =
        "
        SELECT
            title,
            description,
            notes,
            source
        FROM
            tarot_card_notes
        WHERE
            card_id = ?
        AND
            orientation = ?
        AND
            enabled = 1
        ";
    $stmt =
        $conn->prepare(
            $sql
        );
    $stmt->bind_param(
        "is",
        $card_id,
        $orientation
);
    $stmt->execute();
    $result =
        $stmt->get_result();
    $notes = [];
    while (
        $row =
        $result->fetch_assoc()
    )
    {
        $notes[] =
            $row;
    }
    return
        $notes;
}
/*
============================================================
Trim POST value
============================================================
*/
function post($field)
{
    return trim(
        $_POST[$field] ?? ""
    );
}
/*
============================================================
Display message
============================================================
*/
function display_message($message)
{
    echo
    "<div
        style='
            background:#162447;
            border:2px solid #e43f5a;
            border-radius:8px;
            padding:15px;
            margin-bottom:20px;
            color:white;
        '>";
    echo h($message);
    echo "</div>";
}
/*
============================================================
Get one Study Note
============================================================
*/
function get_study_note(
    mysqli $conn,
    int $id
)
{
    $sql = "
        SELECT
            *
        FROM
            tarot_card_notes
        WHERE
            id = ?
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
    {
        return null;
    }
    $stmt->bind_param(
        "i",
        $id
    );
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}
/*
============================================================
Update Study Note
============================================================
*/
function update_study_note(
    mysqli $conn,
    int $id,
    string $title,
    string $description,
    string $source,
    string $notes,
    bool $enabled
)
{
    $sql = "
        UPDATE
            tarot_card_notes
        SET
            title = ?,
            description = ?,
            source = ?,
            notes = ?,
            enabled = ?
        WHERE
            id = ?
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
    {
        return false;
    }
    $enabled_int = $enabled ? 1 : 0;
    $stmt->bind_param(
        "ssssii",
        $title,
        $description,
        $source,
        $notes,
        $enabled_int,
        $id
    );
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
function delete_study_note(
    mysqli $conn,
    int $id
)
{
    /*
    --------------------------------------------
    Find the note so we know what to resequence.
    --------------------------------------------
    */
    $stmt = $conn->prepare(
        "
        SELECT
            card_id,
            orientation,
            sequence_no
        FROM
            tarot_card_notes
        WHERE
            id = ?
        "
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0)
    {
        $stmt->close();
        return false;
    }
    $row = $result->fetch_assoc();
    $card_id     = intval($row['card_id']);
    $orientation = $row['orientation'];
    $sequence_no = intval($row['sequence_no']);
    $stmt->close();
    /*
    --------------------------------------------
    Delete the selected note.
    --------------------------------------------
    */
    $stmt = $conn->prepare(
        "
        DELETE
        FROM
            tarot_card_notes
        WHERE
            id = ?
        "
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    /*
    --------------------------------------------
    Close the sequence gap.
    --------------------------------------------
    */
    $stmt = $conn->prepare(
        "
        UPDATE
            tarot_card_notes
        SET
            sequence_no = sequence_no - 1
        WHERE
            card_id = ?
        AND
            orientation = ?
        AND
            sequence_no > ?
        "
    );
    $stmt->bind_param(
        "isi",
        $card_id,
        $orientation,
        $sequence_no
    );
    $stmt->execute();
    $stmt->close();
    return true;
}
?>
