<?php

function get_recent_readings(
    mysqli $conn,
    int $limit = 25
)
{
    $sql =
        "SELECT
            id,
            created_at,
            user_question,
            reading_type,
            card_ids,
            card_orientations
         FROM tarot_readings
         ORDER BY id DESC
         LIMIT ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $limit
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $rows =
        $result->fetch_all(
            MYSQLI_ASSOC
        );

    $stmt->close();

    return $rows;
}
function get_card_name(
    mysqli $conn,
    int $card_id
)
{
    $sql =
        "SELECT card_name
         FROM tarot_cards
         WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $card_id
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    $row =
        $result->fetch_assoc();

    $stmt->close();

    if ($row)
    {
        return $row['card_name'];
    }

    return "[Unknown Card]";
}
