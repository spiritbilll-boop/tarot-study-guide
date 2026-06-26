<?php
/*
============================================================

    study_notes_common.php

    Common functions for the Tarot Study Notes Manager

    Version 1.1

============================================================
*/

$mysqli = new mysqli(
    "localhost",
    "tarot_db",
    "tarot_db",
    "tarot_db"
);


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
Insert Study Note
============================================================
*/

function insert_study_note(
    mysqli $conn,
    int $card_id,
    string $orientation,
    int $sequence_no,
    string $title,
    string $description,
    string $source,
    bool $enabled,
    string $notes
)
{
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

    return $stmt->execute();
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

?>
