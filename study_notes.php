<?php

/*
=========================================================

study_notes.php

Tarot Study Guide
Study Note Manager

Version 1.1

=========================================================
*/

require_once("database.php");
require_once("study_notes_common.php");

$message = "";

$card_id = 1;
$orientation = "U";
$sequence_no = 1;
$title = "";
$description = "";
$source = "";
$enabled = 1;
$notes = "";

$cards = get_cards($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $card_id = intval($_POST['card_id']);
    $orientation = $_POST['orientation'];
    $sequence_no = intval($_POST['sequence_no']);

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $source = trim($_POST['source']);

    $enabled = isset($_POST['enabled']) ? 1 : 0;

    $notes = trim($_POST['notes']);

    if ($title == "")
    {
        $message = "Title is required.";
    }
    elseif ($description == "")
    {
        $message = "Description is required.";
    }
    else
    {
        if
        (
            insert_study_note
            (
                $conn,
                $card_id,
                $orientation,
                $sequence_no,
                $title,
                $description,
                $source,
                $enabled,
                $notes
            )
        )
        {
            $message = "Study Note saved successfully.";

            $sequence_no =
                get_next_sequence
                (
                    $conn,
                    $card_id,
                    $orientation
                );

            $title = "";
            $description = "";
            $source = "";
            $notes = "";

            $enabled = 1;
        }
        else
        {
            $message = "Database insert failed.";
        }
    }
}
else
{
    $sequence_no =
        get_next_sequence
        (
            $conn,
            $card_id,
            $orientation
        );
}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Tarot Study Notes</title>

<style>

body
{
    background-color:#555555;
    color:white;
    font-family:"Times New Roman", Times, serif;
    margin:20px;
    line-height:1.5;
}

.container
{
    max-width:1100px;
    margin:auto;
}

.section
{
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:20px;
    margin-top:20px;
}

h1
{
    text-align:center;
    font-size:48px;
}

h2
{
    text-align:center;
    font-size:30px;
}

table
{
    width:100%;
}

td
{
    padding:8px;
    vertical-align:top;
}

input[type=text]
{
    width:100%;
    font-size:18px;
    padding:6px;
}

input[type=number]
{
    width:120px;
    font-size:18px;
    padding:6px;
}

textarea
{
    width:100%;
    height:220px;
    font-size:18px;
    padding:8px;
    font-family:"Times New Roman", Times, serif;
}

select
{
    font-size:18px;
    padding:6px;
}

.button
{
    display:inline-block;
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:12px 24px;
    color:white;
    text-decoration:none;
    font-size:20px;
    font-weight:bold;
    cursor:pointer;
}

.button:hover
{
    background:#e43f5a;
}

.message
{
    background:#1b5e20;
    border:2px solid #81c784;
    border-radius:8px;
    padding:15px;
    margin-bottom:20px;
}

.error
{
    background:#7f0000;
    border:2px solid #ff8080;
    border-radius:8px;
    padding:15px;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<h1>Tarot Study Guide</h1>

<h2>Study Notes Manager</h2>

<?php

if ($message != "")
{
    echo '<div class="message">';
    echo h($message);
    echo '</div>';
}

?>

<div class="section">

<form
method="post"
action="study_notes.php">

<table>

<tr>

<td width="220">

<b>Card</b>

</td>

<td>

<select
name="card_id">

<?php

foreach($cards as $card)
{

?>

<option
value="<?php echo $card['id']; ?>"

<?php

if($card['id']==$card_id)
{
    echo "selected";
}

?>

>

<?php

echo h($card['card_name']);

?>

</option>

<?php

}

?>

</select>

</td>

</tr>

<tr>

<td>

<b>Orientation</b>

</td>

<td>

<label>

<input
type="radio"
name="orientation"
value="U"

<?php

if($orientation=="U")
{
    echo "checked";
}

?>

>

Upright

</label>

&nbsp;&nbsp;&nbsp;

<label>

<input
type="radio"
name="orientation"
value="R"

<?php

if($orientation=="R")
{
    echo "checked";
}

?>

>

Reversed

</label>

</td>

</tr>

<tr>

<td>

<b>Sequence Number</b>

</td>

<td>

<input
type="number"
name="sequence_no"
value="<?php echo $sequence_no; ?>">

</td>

</tr>

<tr>

<td>

<b>Title</b>

</td>

<td>

<input
type="text"
name="title"
value="<?php echo h($title); ?>">

</td>

</tr>
<tr>

<td>

<b>Description</b>

</td>

<td>

<textarea
name="description"><?php echo h($description); ?></textarea>

</td>

</tr>

<tr>

<td>

<b>Source</b>

</td>

<td>

<input
type="text"
name="source"
value="<?php echo h($source); ?>">

</td>

</tr>

<tr>

<td>

<b>Enabled</b>

</td>

<td>

<label>

<input
type="checkbox"
name="enabled"

<?php

if($enabled)
{
    echo "checked";
}

?>

>

Display this Study Note

</label>

</td>

</tr>

<tr>

<td>

<b>Editorial Notes</b>

</td>

<td>

<textarea
name="notes"
style="height:120px;"><?php echo h($notes); ?></textarea>

</td>

</tr>

<tr>

<td>

</td>

<td>

<input
class="button"
type="submit"
value="Save Study Note">

&nbsp;

<input
class="button"
type="reset"
value="Clear Form">

&nbsp;

<a
class="button"
href="index.php">

Return Home

</a>

</td>

</tr>

</table>

</form>

</div>
<?php

$sql = "

SELECT

    note_id,
    sequence_no,
    title,
    enabled

FROM

    tarot_card_notes

WHERE

    card_id=?

AND

    orientation=?

ORDER BY

    sequence_no

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

    "is",

    $card_id,

    $orientation

);

$stmt->execute();

$result = $stmt->get_result();

?>

<div class="section">

<h2>

Current Study Notes

</h2>

<table>

<tr>

<th align="left">

Seq

</th>

<th align="left">

Title

</th>

<th align="center">

Enabled

</th>

</tr>

<?php

while($row = $result->fetch_assoc())

{

?>

<tr>

<td>

<?php echo $row['sequence_no']; ?>

</td>

<td>

<?php echo h($row['title']); ?>

</td>

<td align="center">

<?php

echo $row['enabled']

? "Yes"

: "No";

?>

</td>

</tr>

<?php

}

?>

</table>

</div>
