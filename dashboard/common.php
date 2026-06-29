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
