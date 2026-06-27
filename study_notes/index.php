
<?php

/*
============================================================

study_notes/index.php

Study Notes Manager

Version 1.1

============================================================
*/

require_once("../database.php");
require_once("common.php");

$cards = get_cards($conn);

$card_id =
    isset($_GET['card_id'])
        ? intval($_GET['card_id'])
        : 1;

$orientation =
    isset($_GET['orientation'])
        ? $_GET['orientation']
        : "U";

$next_sequence =
    get_next_sequence(
        $conn,
        $card_id,
        $orientation
    );

$notes =
    get_notes(
        $conn,
        $card_id,
        $orientation
    );

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>

Study Notes Manager

</title>

</head>

<body>

<h1>

Study Notes Manager

</h1>

<p>

Cards Loaded:

<b>

<?php echo count($cards); ?>
</b>
</p>
<form method="get">
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
foreach($cards as $card)
{

?>

<option
value="<?php echo $card['id']; ?>"

<?php

if($card['id'] == $card_id)
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

if($orientation == "U")
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

if($orientation == "R")
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

</form>

<p>

Next Sequence:

<b>

<?php echo $next_sequence; ?>

</b>

</p>

<p>

Existing Notes:

<b>

<?php echo count($notes); ?>

</b>

</p>

</body>

</html>
