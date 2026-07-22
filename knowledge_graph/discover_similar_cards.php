<?php

require_once("../common/db.php");

$card_id = isset($_GET["id"]) ? intval($_GET["id"]) : 1;

/*
** Get selected card name
*/

$sql = "
SELECT
    card_name
FROM
    tarot_cards
WHERE
    id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $card_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

$card_name = $row ? $row["card_name"] : "Unknown Card";

/*
** Find similar cards
*/

$sql = "
SELECT
    c2.id,
    c2.card_name,
    COUNT(*) AS shared_topics
FROM entity_topics et1

JOIN entity_topics et2
    ON et1.topic_id = et2.topic_id

JOIN tarot_cards c2
    ON c2.id = et2.entity_id

WHERE
    et1.entity_id = ?
AND
    et2.entity_id <> ?

GROUP BY
    c2.id,
    c2.card_name

ORDER BY
    shared_topics DESC,
    c2.card_name
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "ii", $card_id, $card_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Discover Similar Cards</title>

<link rel="stylesheet" href="../style.css">

<style>

table
{
    border-collapse: collapse;
    width: 100%;
}

th,
td
{
    border: 1px solid #808080;
    padding: 8px;
    text-align: left;
}

th
{
    background: #e8e8e8;
}

tr:nth-child(even)
{
    background: #f7f7f7;
}

.footer
{
    margin-top: 20px;
    font-style: italic;
}

.numeric
{
    text-align: right;
}

</style>

</head>

<body>

<h1>

Knowledge Graph Explorer

</h1>

<h2>

Cards Similar To

</h2>

<h3>

<?php echo htmlspecialchars($card_name); ?>

</h3>

<p>

<a href="card.php?id=<?php echo $card_id; ?>">

Return to Card

</a>

|

<a href="index.php">

Return to Knowledge Graph Explorer

</a>

|

<a href="../index.php">

Return to Main Menu

</a>

</p>

<table>

<tr>

<th>

Shared Topics

</th>

<th>

Card ID

</th>

<th>

Card

</th>

</tr>

<?php

while ($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td class="numeric">

<?php echo $row["shared_topics"]; ?>

</td>

<td class="numeric">

<a href="card.php?id=<?php echo $row["id"]; ?>">

<?php echo $row["id"]; ?>

</a>

</td>

<td>

<a href="card.php?id=<?php echo $row["id"]; ?>">

<?php echo htmlspecialchars($row["card_name"]); ?>

</a>

</td>

</tr>

<?php

}

?>

</table>

<p class="footer">

Cards are ranked by the number of symbolic topics they share with the selected card.

</p>

</body>

</html>
