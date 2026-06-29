<?php
require_once
dirname(__DIR__) .
"/database.php";
/*
============================================================
Dashboard Helper Functions
============================================================
*/
function get_study_note_count(
    mysqli $conn
)
{
    $sql =
        "
        SELECT
            COUNT(*)
        AS
            total
        FROM
            tarot_card_notes
        ";
    $result =
        $conn->query(
            $sql
        );
    $row =
        $result->fetch_assoc();
    return
        intval(
            $row['total']
        );
}
function get_card_count(
    mysqli $conn
)
{
    $sql =
        "
        SELECT
            COUNT(*)
        AS
            total
        FROM
            tarot_cards
        ";
    $result =
        $conn->query(
            $sql
        );
    $row =
        $result->fetch_assoc();
    return
        intval(
            $row['total']
        );
}
function get_enabled_study_note_count(
    mysqli $conn
)
{
    $sql =
        "
        SELECT
            COUNT(*)
        AS
            total
        FROM
            tarot_card_notes
        WHERE
            enabled = 1
        ";
    $result =
        $conn->query(
            $sql
        );
    $row =
        $result->fetch_assoc();
    return
        intval(
            $row['total']
        );
}
function get_disabled_study_note_count(
    mysqli $conn
)
{
    $sql =
        "
        SELECT
            COUNT(*)
        AS
            total
        FROM
            tarot_card_notes
        WHERE
            enabled = 0
        ";
    $result =
        $conn->query(
            $sql
        );
    $row =
        $result->fetch_assoc();
    return
        intval(
            $row['total']
        );
}
function get_cards_with_study_notes(
    mysqli $conn
)
{
    $sql =
        "
        SELECT
            COUNT(DISTINCT card_id)
        AS
            total
        FROM
            tarot_card_notes
        WHERE
            enabled = 1
        ";
    $result =
        $conn->query(
            $sql
        );
    $row =
        $result->fetch_assoc();
    return
        intval(
            $row['total']
        );
}
function get_cards_without_study_notes(
    mysqli $conn
)
{
    return
        get_card_count(
            $conn
        )
        -
        get_cards_with_study_notes(
            $conn
        );
}
function get_coverage_percentage(
    mysqli $conn
)
{
    $cards =
        get_card_count(
            $conn
        );

    if (
        $cards == 0
    )
    {
        return 0.0;
    }

    return
        (
            get_cards_with_study_notes(
                $conn
            )
            /
            $cards
        )
        * 100.0;
}
