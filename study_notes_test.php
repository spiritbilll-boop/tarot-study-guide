<?php

/*
============================================================

study_notes_test.php

Test program for Study Notes Manager

============================================================
*/

require_once("database.php");
require_once("study_notes_common.php");

$cards = get_cards($conn);
$next_seq_u = get_next_sequence($conn, 1, "U");
$next_seq_r = get_next_sequence($conn, 1, "R");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Study Notes Test</title>

<style>

body
{
    background:#555555;
    color:white;
    font-family:"Times New Roman", Times, serif;
    margin:20px;
}

.container
{
    max-width:900px;
    margin:auto;
}

.section
{
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:20px;
}

select
{
    font-size:20px;
    padding:6px;
}

</style>

</head>

<body>

<div class="container">

<div class="section">

<h1>Study Notes Test</h1>

<p>

If you can see all 78 Tarot cards in the drop-down list,
the helper library is working correctly.

</p>

<select>

<?php

foreach($cards as $card)
{

?>

<option value="<?php echo $card['id']; ?>">

<?php

echo htmlspecialchars($card['card_name']);

?>

</option>

<?php

}

?>

</select>

<p>

Total Cards Loaded:
<hr>

<h2>Sequence Number Test</h2>

<p>

<b>Ace of Cups (Upright)</b>

<br>

Next Sequence Number:

<?php echo $next_seq_u; ?>

</p>

<p>

<b>Ace of Cups (Reversed)</b>

<br>

Next Sequence Number:

<?php echo $next_seq_r; ?>

</p>

<b>

<?php echo count($cards); ?>

</b>

</p>

</div>

</div>

</body>

</html>
