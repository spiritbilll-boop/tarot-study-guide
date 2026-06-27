<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>
Study Notes Manager
</title>
<style>
body
{
    font-family: Arial, Helvetica, sans-serif;
    margin: 25px;
}
table
{
    border-collapse: collapse;
}

th,
td
{
    padding: 6px;
    vertical-align: top;
}

.message
{
    color: green;
    font-weight: bold;
}

.error
{
    color: red;
    font-weight: bold;
}
.notes-table
{
    border-collapse: collapse;
    margin-top: 10px;
}
.notes-table th,
.notes-table td
{
    border: 1px solid #808080;
    padding: 6px 10px;
}
textarea
{
    width: 700px;
}
input[type=text]
{
    width: 500px;
}
</style>
</head>
<body>
<h1>
Study Notes Manager
</h1>
<?php

$is_edit =
    ($edit_note !== null);

$form_title =
    $is_edit
        ? "Edit Study Note"
        : "New Study Note";

$button_text =
    $is_edit
        ? "Update Study Note"
        : "Save Study Note";

$title_value =
    $edit_note['title'] ?? "";

$description_value =
    $edit_note['description'] ?? "";

$source_value =
    $edit_note['source'] ?? "";

$notes_value =
    $edit_note['notes'] ?? "";

$enabled_value =
    isset($edit_note)
        ? intval($edit_note['enabled'])
        : 1;

?>
<?php

if ($message != "")
{

?>

<p class="message">

<?php echo h($message); ?>

</p>

<?php

}

?>

<form
method="post"
action="">

<table>

<tr>

<td>

<b>Card</b>

</td>

<td>

<select
name="card_id"
onchange="this.form.submit()">

<?php

foreach ($cards as $card)
{

?>

<option
value="<?php echo $card['id']; ?>"

<?php

if ($card['id'] == $card_id)
{
    echo " selected";
}

?>

>

<?php echo h($card['card_name']); ?>

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

if ($orientation == "U")
{
    echo " checked";
}

?>

onchange="this.form.submit()">

Upright

</label>

&nbsp;&nbsp;

<label>

<input
type="radio"
name="orientation"
value="R"

<?php

if ($orientation == "R")
{
    echo " checked";
}

?>

onchange="this.form.submit()">

Reversed

</label>

</td>

</tr>

</table>

<hr>
<p>
<b>
Next Sequence:
</b>
<?php echo $next_sequence; ?>
</p>

<h2>
<?php echo $form_title; ?>
</h2>

<?php
if (count($notes) == 0)
{
?>
<p>
No study notes yet.
</p>
<?php
}
else
{
?>
<table class="notes-table">

<tr>
<th>
Seq
</th>
<th>
Title
</th>
<th>
Action
</th>
<?php
foreach ($notes as $note)
{
?>
<tr>
<td>
<?php echo $note['sequence_no']; ?>
</td>
<td>
<?php echo h($note['title']); ?>
</td>

<td>
<a href="?card_id=<?php
echo $card_id;
?>&orientation=<?php
echo $orientation;
?>&edit=<?php
echo $note['id'];
?>">
Edit
</a>
</td>
</tr>

<?php
}
?>
</table>
<?php
}
?>
<hr>
<h2>
New Study Note
</h2>
<table>
<tr>
<td>
Title
</td>
<td>

<input
type="text"
name="title"
value="<?php echo h($title_value); ?>">

</td>

</tr>

<tr>

<td>

Description

</td>

<td>

<textarea
name="description"
rows="8"><?php echo h($description_value); ?></textarea>

</td>

</tr>

<tr>

<td>

Source

</td>

<td>

<input
type="text"
name="source"
value="<?php echo h($source_value); ?>">

</td>

</tr>

<tr>

<td>

Editorial Notes

</td>

<td>
<textarea
name="notes"
rows="3"><?php
echo h($notes_value);
?></textarea>
</td>
</tr>

<tr>
<td>
Enabled
</td>
<td>
<input
type="checkbox"
name="enabled"
<?php if ($enabled_value) echo "checked"; ?>
>

</td>

</tr>

<tr>

<td>

&nbsp;

</td>

<td>

<input
type="submit"
value="<?php echo $button_text; ?>">

</td>

</tr>

</table>

</form>

</body>

</html>
