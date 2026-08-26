<?php

require_once("../common/db.php");

$card_id = isset($_GET["id"]) ? intval($_GET["id"]) : 1;

/*
** Retrieve card information
*/

$sql = "
SELECT
    id,
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

$card = mysqli_fetch_assoc($result);

if (!$card)
{
    die("Card not found.");
}

/*
** Retrieve topics associated with this card
*/

$sql = "
SELECT
    d.name AS domain_name,
    t.name AS topic_name
FROM
    entity_topics et

JOIN topics t
    ON et.topic_id = t.id

JOIN domains d
    ON t.domain_id = d.id

WHERE
    et.entity_id = ?

ORDER BY
    d.name,
    t.name
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $card_id);

mysqli_stmt_execute($stmt);

$topics = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Card</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/theme.css">
<link rel="stylesheet" href="knowledge_graph.css">

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

.actions
{
    margin-top: 25px;
}

.actions ul
{
    margin: 0;
    padding-left: 20px;
}

</style>

</head>

<body>

<h1>

Knowledge Graph Explorer

</h1>

<h2>

Card

</h2>

<h3>

<?php

echo $card["id"] . " &mdash; " . htmlspecialchars($card["card_name"]);

?>

</h3>

<h3>

Topics

</h3>

<table>

<tr>

<th>

Domain

</th>

<th>

Topic

</th>

</tr>

<?php

while ($row = mysqli_fetch_assoc($topics))
{

?>

<tr>

<td>

<?php echo htmlspecialchars($row["domain_name"]); ?>

</td>

<td>

<?php echo htmlspecialchars($row["topic_name"]); ?>

</td>

</tr>

<?php

}

?>

</table>

<div class="actions">

<h3>

Actions

</h3>

<ul>

<li>

<a href="discover_similar_cards.php?id=<?php echo $card_id; ?>">

Discover Similar Cards

</a>

</li>

<li>

<a href="topics_by_card.php?id=<?php echo $card_id; ?>">

Topics by Card

</a>

</li>

<li>

<a href="index.php">

Return to Knowledge Graph Explorer

</a>

</li>

<li>

<a href="../index.php">

Return to Main Menu

</a>

</li>

</ul>

</div>

</body>

</html>
