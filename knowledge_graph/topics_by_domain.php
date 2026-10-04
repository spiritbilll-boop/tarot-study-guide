<?php

require_once("../common/db.php");

$sql = "
SELECT
    d.id             AS domain_id,
    d.name           AS domain,
    t.id             AS topic_id,
    t.description    AS topic
FROM domains d
JOIN topics t
    ON t.domain_id = d.id
WHERE d.enabled = 1
  AND t.enabled = 1
ORDER BY
    d.name,
    t.name";

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

<title>Knowledge Graph Explorer - Topics by Domain</title>

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

.footer
{
    margin-top: 20px;
    font-style: italic;
}

</style>

</head>

<body>

<h1>Knowledge Graph Explorer</h1>

<h2>Topics by Domain</h2>

<p>

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

<th>Domain</th>
<th>Topic</th>
<th>Domain ID</th>
<th>Topic ID</th>

</tr>

<?php foreach ($rows as $row): ?>

<tr>
<td>
<a href="topics.php?domain_id=<?= $row['domain_id'] ?>">
<?= htmlspecialchars($row['domain']) ?>
</a>
</td>

<td>
<a href="topic.php?id=<?= $row['topic_id'] ?>">
<?= htmlspecialchars($row['topic']) ?>
</a>
</td>

<td>
<?= $row['domain_id'] ?>
</td>

<td>
<?= $row['topic_id'] ?>
</td>

</tr>

<?php endforeach; ?>

</table>

<p class="footer">

<?= count($rows) ?> rows returned.

</p>

</body>

</html>
