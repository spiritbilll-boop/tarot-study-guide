<?php

/*
 * common/stats.php
 *
 * Reusable statistics functions for the Tarot Study Guide.
 */

require_once __DIR__ . '/db.php';

/*
 * Execute a query and return all rows.
 */
function stats_query(mysqli $conn, string $sql): array
{
    $result = $conn->query($sql);

    if (!$result)
    {
        throw new Exception($conn->error);
    }

    $rows = [];

    while ($row = $result->fetch_assoc())
    {
        $rows[] = $row;
    }

    $result->free();

    return $rows;
}

/*
 * Cards having no enabled Study Notes.
 */
function get_cards_without_notes(mysqli $conn): array
{
    $sql = "
        SELECT
            tc.id,
            tc.card_name
        FROM tarot_cards tc
        LEFT JOIN study_notes sn
               ON sn.card_id = tc.id
              AND sn.enabled = 1
        WHERE sn.id IS NULL
        ORDER BY tc.id
    ";

    return stats_query($conn, $sql);
}

/*
 * Cards having only Upright notes.
 */
function get_cards_with_upright_only(mysqli $conn): array
{
    $sql = "
        SELECT
            tc.id,
            tc.card_name
        FROM tarot_cards tc
        WHERE EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'U'
              AND sn.enabled = 1
        )
        AND NOT EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'R'
              AND sn.enabled = 1
        )
        ORDER BY tc.id
    ";

    return stats_query($conn, $sql);
}

/*
 * Cards having only Reversed notes.
 */
function get_cards_with_reversed_only(mysqli $conn): array
{
    $sql = "
        SELECT
            tc.id,
            tc.card_name
        FROM tarot_cards tc
        WHERE EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'R'
              AND sn.enabled = 1
        )
        AND NOT EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'U'
              AND sn.enabled = 1
        )
        ORDER BY tc.id
    ";

    return stats_query($conn, $sql);
}

/*
 * Cards having both Upright and Reversed notes.
 */
function get_cards_with_both_orientations(mysqli $conn): array
{
    $sql = "
        SELECT
            tc.id,
            tc.card_name
        FROM tarot_cards tc
        WHERE EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'U'
              AND sn.enabled = 1
        )
        AND EXISTS
        (
            SELECT 1
            FROM study_notes sn
            WHERE sn.card_id = tc.id
              AND sn.orientation = 'R'
              AND sn.enabled = 1
        )
        ORDER BY tc.id
    ";

    return stats_query($conn, $sql);
}

/*
 * Number of enabled notes for every card.
 */
function get_note_counts(mysqli $conn): array
{
    $sql = "
        SELECT
            tc.id,
            tc.card_name,
            COUNT(sn.id) AS note_count
        FROM tarot_cards tc
        LEFT JOIN study_notes sn
               ON sn.card_id = tc.id
              AND sn.enabled = 1
        GROUP BY
            tc.id,
            tc.card_name
        ORDER BY
            tc.id
    ";

    return stats_query($conn, $sql);
}

/*
 * Total enabled Study Notes.
 */
function get_total_note_count(mysqli $conn): int
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM study_notes
        WHERE enabled = 1
    ";

    $rows = stats_query($conn, $sql);

    return intval($rows[0]['total']);
}

/*
 * Total cards.
 */
function get_total_card_count(mysqli $conn): int
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM tarot_cards
    ";

    $rows = stats_query($conn, $sql);

    return intval($rows[0]['total']);
}

?>
