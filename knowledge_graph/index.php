<?php

/*
============================================================

Tarot Study Guide

Knowledge Graph

Topics by Domain

============================================================
*/

require_once __DIR__ . '/../common/db.php';

$sql = "

SELECT
    d.id             AS domain_id,
    d.name           AS domain,
    d.display_order  AS domain_display_order,
    t.id             AS topic_id,
    t.name           AS topic,
    t.display_order  AS topic_display_order
FROM domains d
JOIN topics t
    ON t.domain_id = d.id
WHERE d.enabled = 1
  AND t.enabled = 1
ORDER BY
    d.name,
    t.name;

";

$result = $conn->query($sql);

if (!$result)
{
    die("Query failed: " . $conn->error);
}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph - Topics by Domain</title>

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

</style>

</head>

<body>

<h1>Knowledge Graph</h1>

<h2>Topics by Domain</h2>

<p>

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

<?php

$count = 0;

while ($row = $result->fetch_assoc())
{

    $count++;

?>

<tr>

<td><?= htmlspecialchars($row['domain']) ?></td>

<td><?= htmlspecialchars($row['topic']) ?></td>

<td><?= htmlspecialchars($row['domain_id']) ?></td>

<td><?= htmlspecialchars($row['topic_id']) ?></td>

</tr>
<?php

}

?>

</table>

<p class="footer">

<?= $count ?> rows returned.

</p>

</body>

</html>
