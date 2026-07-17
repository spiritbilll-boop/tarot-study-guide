<?php

require_once("../common/db.php");

$sql = "
SELECT
    t.id AS topic_id,
    t.name AS topic,
    COALESCE(d.name,'') AS domain
FROM topics t
LEFT JOIN domains d
    ON d.id = t.domain_id
LEFT JOIN entity_topics et
    ON et.topic_id = t.id
WHERE et.topic_id IS NULL
ORDER BY
    d.name,
    t.name";

$result = $conn->query($sql);

$rows = [];

while ($row = $result->fetch_assoc())
{
    $rows[] = $row;
}

$totalTopics =
    $conn->query("SELECT COUNT(*) AS n FROM topics")
         ->fetch_assoc()['n'];

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Unused Topics</title>

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

<h2>Unused Topics</h2>

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

Every topic is currently associated with at least one tarot card.

</p>

<?php else: ?>

<table>

<tr>

<th>Domain</th>

<th>Topic ID</th>

<th>Topic</th>

</tr>

<?php foreach ($rows as $row): ?>

<tr>

<td><?= htmlspecialchars($row['domain']) ?></td>

<td><?= $row['topic_id'] ?></td>

<td><?= htmlspecialchars($row['topic']) ?></td>

</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

<p class="footer">

<?= count($rows) ?> of <?= $totalTopics ?> topics are not currently associated with any tarot card.

</p>

</body>

</html>
