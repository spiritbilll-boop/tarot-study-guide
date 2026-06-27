<?php

/*
============================================================

study_notes/index.php

Study Notes Manager

Controller

============================================================
*/

require_once(__DIR__ . "/../database.php");
require_once(__DIR__ . "/common.php");

/*
============================================================

Defaults

============================================================
*/

$message = "";

$card_id =
    isset($_REQUEST['card_id'])
        ? intval($_REQUEST['card_id'])
        : 1;

$orientation =
    isset($_REQUEST['orientation'])
        ? $_REQUEST['orientation']
        : "U";

/*
============================================================

Handle Save

============================================================
*/

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $title =
        trim($_POST['title']);

    $description =
        trim($_POST['description']);

    $source =
        trim($_POST['source']);

    $notes_text =
        trim($_POST['notes']);

    $enabled =
        isset($_POST['enabled']) ? 1 : 0;

    if ($title == "")
    {
        $message =
            "Please enter a title.";
    }
    elseif ($description == "")
    {
        $message =
            "Please enter a description.";
    }
    else
    {
        insert_study_note(
            $conn,
            $card_id,
            $orientation,
            $title,
            $description,
            $source,
            $notes_text,
            $enabled
        );

        $message =
            "Study Note saved successfully.";
    }
}

/*
============================================================

Load page data

============================================================
*/

$cards =
    get_cards($conn);

$notes =
    get_notes(
        $conn,
        $card_id,
        $orientation
    );

$next_sequence =
    get_next_sequence(
        $conn,
        $card_id,
        $orientation
    );

/*
============================================================

Display page

============================================================
*/

require_once(__DIR__ . "/form.php");
