<?php
require_once("../common/db.php");

$sql = "
SELECT
    d.name AS domain,
    tc.id AS card_id,
    tc.card_name,
    t.id AS topic_id,
    t.name AS topic
FROM topics t
JOIN entity_topics et
  ON et.topic_id = t.id
JOIN tarot_cards tc
  ON tc.id = et.entity_id
LEFT JOIN domains d
  ON d.id = t.domain_id
ORDER BY
  d.name,
  t.name,
  tc.id";

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
<title>Knowledge Graph Explorer - Cards by Topic</title>
<link rel="stylesheet" href="../style.css">
<style>
table{border-collapse:collapse;width:100%}
th,td{border:1px solid #808080;padding:8px;text-align:left}
th{background:#e8e8e8}
tr:nth-child(even){background:#f7f7f7}
.footer{margin-top:20px;font-style:italic}
</style>
</head>
<body>

<h1>Knowledge Graph Explorer</h1>

<h2>Cards by Topic</h2>

<p>
<a href="index.php">Return to Knowledge Graph Explorer</a> |
<a href="../index.php">Return to Main Menu</a>
</p>

<table>
<tr>
<th>Domain</th>
<th>Topic</th>
<th>Topic ID</th>
<th>Card ID</th>
<th>Card</th>
</tr>

<?php foreach ($rows as $row): ?>
<tr>
<td><?= htmlspecialchars($row['domain']) ?></td>
<td><?= htmlspecialchars($row['topic']) ?></td>
<td><?= $row['topic_id'] ?></td>
<td>
<a href="card.php?id=<?= $row['card_id'] ?>">
<?= $row['card_id'] ?>
</a>
</td>

<td>
<a href="card.php?id=<?= $row['card_id'] ?>">
<?= htmlspecialchars($row['card_name']) ?>
</a>
</td>
</tr>

<?php endforeach; ?>

</table>

<p class="footer">
<?= count($rows) ?> rows returned.
</p>

</body>
</html>
