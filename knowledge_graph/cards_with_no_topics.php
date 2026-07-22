<?php

require_once("../common/db.php");

$sql = "
SELECT
    tc.id AS card_id,
    tc.card_name
FROM tarot_cards tc
LEFT JOIN entity_topics et
    ON et.entity_id = tc.id
WHERE et.entity_id IS NULL
ORDER BY tc.id";
$totalCards =
    $conn->query("SELECT COUNT(*) AS n FROM tarot_cards")
         ->fetch_assoc()['n'];

$result = $conn->query($sql);

$rows = [];

while ($row = $result->fetch_assoc())
{
    $rows[] = $row;
}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Cards with No Topics</title>

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
    margin-top:20px;
    font-style:italic;
}

</style>

</head>

<body>

<h1>Knowledge Graph Explorer</h1>

<h2>Cards with No Topics</h2>

<p>

<a href="index.php">
Return to Knowledge Graph Explorer
</a>

|

<a href="../index.php">
Return to Main Menu
</a>

</p>

<?php if (count($rows) == 0): ?>

<p>

<strong>Excellent!</strong>

Every tarot card currently has at least one topic assigned.

</p>

<?php else: ?>

<table>

<tr>

<th>Card ID</th>

<th>Card</th>

</tr>

<?php foreach ($rows as $row): ?>

<tr>

<td>
<a href="../card.php?id=<?= $row['card_id'] ?>">
<?= $row['card_id'] ?>
</a>
</td>

<td>
<a href="../card.php?id=<?= $row['card_id'] ?>">
<?= htmlspecialchars($row['card_name']) ?>
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

<p class="footer">

<?= count($rows) ?> of <?= $totalCards ?> tarot cards currently have no symbolic topics assigned.

</p>

</body>

</html>
