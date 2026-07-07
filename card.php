<?php

/*
============================================================

Tarot Study Guide

Card Detail Page

Displays one Tarot card together with:

    ? Card image
    ? Upright meaning
    ? Reversed meaning
    ? Study Notes (Upright)
    ? Study Notes (Reversed)

Version 1

============================================================
*/

require_once __DIR__ . '/common/db.php';

require_once __DIR__ . '/study_notes/common.php';

/*
------------------------------------------------------------
Validate card id
------------------------------------------------------------
*/

$card_id =
    isset($_GET['id'])
        ? intval($_GET['id'])
        : 1;

if ($card_id < 1)
{
    $card_id = 1;
}

/*
------------------------------------------------------------
Load Tarot card
------------------------------------------------------------
*/

$sql = "

SELECT

    *

FROM

    tarot_cards

WHERE

    id = ?

";

$stmt =
    $conn->prepare(
        $sql
    );

if (!$stmt)
{
    die(
        $conn->error
    );
}

$stmt->bind_param(
    "i",
    $card_id
);

$stmt->execute();

$result =
    $stmt->get_result();

$card =
    $result->fetch_assoc();

$stmt->close();

if (!$card)
{
    die(
        "Unknown card."
    );
}
/*
------------------------------------------------------------
Load Previous / Next Card Names
------------------------------------------------------------
*/

$previous_card_name = '';
$next_card_name = '';

if ($card_id > 1)
{
    $stmt =
        $conn->prepare(
            "SELECT card_name
             FROM tarot_cards
             WHERE id = ?"
        );

    $previous_id = $card_id - 1;

    $stmt->bind_param(
        "i",
        $previous_id
    );

    $stmt->execute();

    $stmt->bind_result(
        $previous_card_name
    );

    $stmt->fetch();

    $stmt->close();
}

if ($card_id < 78)
{
    $stmt =
        $conn->prepare(
            "SELECT card_name
             FROM tarot_cards
             WHERE id = ?"
        );

    $next_id = $card_id + 1;

    $stmt->bind_param(
        "i",
        $next_id
    );

    $stmt->execute();

    $stmt->bind_result(
        $next_card_name
    );

    $stmt->fetch();

    $stmt->close();
}
/*
------------------------------------------------------------
Load Study Notes
------------------------------------------------------------
*/

$upright_notes =
    get_enabled_notes_for_card(
        $conn,
        $card_id,
        "U"
    );

$reversed_notes =
    get_enabled_notes_for_card(
        $conn,
        $card_id,
        "R"
    );

/*
------------------------------------------------------------
HTML
------------------------------------------------------------
*/
?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>

<?php
echo htmlspecialchars(
    $card['card_name']
);
?>

</title>

<style>

body
{
    background:#1d3557;

    color:white;

    font-family:Arial,Helvetica,sans-serif;

    margin:30px;
}

h1
{
    text-align:center;

    color:#ffd966;
}

h2
{
    color:#ffd966;
}

.panel
{
    background:white;

    color:#222;

    border-radius:10px;

    padding:20px;

    margin-top:25px;

    margin-bottom:25px;
}

.card-image
{
    display:block;

    margin:auto;

    max-width:320px;
}

.back-link
{
    color:#1d3557;
    text-decoration:underline;
    font-weight:bold;
}

.back-link:hover
{
    text-decoration:underline;
}

.meaning
{
    line-height:1.7;

    font-size:18px;
}

.note
{
    background:#f7f7f7;

    border-left:6px solid #4b6cb7;

    border-radius:8px;

    padding:18px;

    margin-bottom:20px;
}

.note-title
{
    font-size:22px;

    font-weight:bold;

    margin-bottom:10px;
}

.note-source
{
    margin-top:15px;

    font-size:14px;

    color:#666;

    text-align:right;
}
.nav-card-name
{
    display:block;

    margin-top:6px;

    font-size:18px;

    font-weight:bold;

    color:#1d3557;
}

</style>

</head>

<body>

<p>

<a
class="back-link"
href="index.php">

? Return to Reader

</a>

</p>

<h1>

<?php
echo htmlspecialchars(
    $card['card_name']
);
?>

</h1>

<img

class="card-image"

src="cards/<?php
echo htmlspecialchars(
    $card['image_file']
);
?>"

alt="<?php
echo htmlspecialchars(
    $card['card_name']
);
?>">
<div class="panel">

<table
style="
width:100%;
border-collapse:collapse;
">

<tr>

<td
style="text-align:left; vertical-align:top;">

<?php

if ($card_id > 1)
{

?>

<a
class="back-link"
href="card.php?id=<?php
echo $card_id - 1;
?>">

&larr;Previous Card

<br>

<span class="nav-card-name">

<?php
echo htmlspecialchars(
  $previous_card_name
);
?>

</span>

<?php

}

?>

</td>

<td
style="text-align:center;">

<a
class="back-link"
href="index.php">

Return to Reader

</a>

</td>

<td
style="text-align:right; vertical-align:top;">

<?php

if ($card_id < 78)
{

?>

<a
class="back-link"
href="card.php?id=<?php
echo $card_id + 1;
?>">

Next Card &rarr;

<br>

<span class="nav-card-name">

<?php
echo htmlspecialchars(
    $next_card_name
);
?>

</span>

</a>

<?php

}

?>

</td>

</tr>

</table>

</div>

<div class="panel">

<h2>

Upright Meaning

</h2>

<div class="meaning">

<?php

echo nl2br(

    h(

        $card['meaning_upright']

    )

);

?>

</div>

</div>

<div class="panel">

<h2>

Reversed Meaning

</h2>

<div class="meaning">

<?php

echo nl2br(

    h(

        $card['meaning_reversed']

    )

);

?>

</div>

</div>
<div class="panel">

<h2>

Study Notes (Upright)

</h2>

<?php

if (count($upright_notes) == 0)
{

?>

<p>

No enabled Upright Study Notes.

</p>

<?php

}
else
{

    foreach ($upright_notes as $note)
    {

?>

<div class="note">

<div class="note-title">

<?php
echo h(
    $note['title']
);
?>

</div>

<?php

if (!empty($note['description']))
{

?>

<p>

<?php

echo nl2br(

    h(

        $note['description']

    )

);

?>

</p>

<?php

}

?>

<div class="note-source">

Source:

<?php
echo h(
    $note['source']
);
?>

</div>

</div>

<?php

    }

}

?>

</div>

<div class="panel">

<h2>

Study Notes (Reversed)

</h2>

<?php

if (count($reversed_notes) == 0)
{

?>

<p>

No enabled Reversed Study Notes.

</p>

<?php

}
else
{

    foreach ($reversed_notes as $note)
    {

?>

<div class="note">

<div class="note-title">

<?php
echo h(
    $note['title']
);
?>

</div>

<?php

if (!empty($note['description']))
{

?>

<p>

<?php

echo nl2br(

    h(

        $note['description']

    )

);

?>

</p>

<?php

}

?>

<div class="note-source">

Source:

<?php
echo h(
    $note['source']
);
?>

</div>

</div>

<?php

    }

}

?>

</div>
<div class="panel">

<table
style="
width:100%;
border-collapse:collapse;
">

<tr>

<td
style="text-align:left;">

<?php

if ($card_id > 1)
{

?>

<a
class="back-link"
href="card.php?id=<?php
echo $card_id - 1;
?>">

&larr; Previous Card

<br>

<span class="nav-card-name">

<?php
echo htmlspecialchars(
    $previous_card_name
);
?>

</span>

</a>

<?php

}

?>

</td>

<td
style="text-align:center;">

<a
class="back-link"
href="index.php">

Return to Reader

</a>

</td>

<td
style="text-align:right; vertical-align:top;"

<?php

if ($card_id < 78)
{

?>

<a
class="back-link"
href="card.php?id=<?php
echo $card_id + 1;
?>">

Next Card &rarr;

<br>

<span class="nav-card-name">

<?php
echo htmlspecialchars(
    $next_card_name
);
?>

</span>

</a>

<?php

}

?>

</td>

</tr>

</table>

</div>

<div
style="
margin-top:40px;
text-align:center;
">

<p>

<a
class="back-link"
href="study_notes/editor.php">

Study Notes Manager

</a>

</p>

<p>

<a
class="back-link"
href="index.php">

New Reading

</a>

</p>

<p>

<a
class="back-link"
href="/">

Main Page

</a>

</p>

</div>

<hr>

<p
style="
text-align:center;
font-size:14px;
color:#cccccc;
">

Last Modified:

<?php

echo strftime(

    '%c',

    filemtime(

        $_SERVER['SCRIPT_FILENAME']

    )

);

?>

</p>

</body>

</html>
