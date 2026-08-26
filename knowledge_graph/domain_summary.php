<?php

require_once("../common/db.php");

$sql = "
SELECT
  d.name AS domain,
  COUNT(DISTINCT t.id) AS total_topics,
  COUNT(DISTINCT et.topic_id) AS used_topics,
  COUNT(DISTINCT et.entity_id) AS cards
FROM domains d
LEFT JOIN topics t
    ON t.domain_id = d.id
LEFT JOIN entity_topics et
    ON et.topic_id = t.id
GROUP BY
    d.id,
    d.name
ORDER BY
    d.name";

$result = $conn->query($sql);

$rows = [];

while ($row = $result->fetch_assoc())
{
    $row['unused_topics'] =
        $row['total_topics'] - $row['used_topics'];

    if ($row['total_topics'] > 0)
    {
        $row['percent_complete'] =
            round(
                100.0 * $row['used_topics'] /
                $row['total_topics'],
                1
            );
    }
    else
    {
        $row['percent_complete'] = 0;
    }

    $rows[] = $row;
}

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Domain Summary</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/theme.css">
<link rel="stylesheet" href="knowledge_graph.css">
<style>

table
{
    border-collapse: collapse;
    width:100%;
}

th,
td
{
    border:1px solid #808080;
    padding:8px;
    text-align:left;
}

td.numeric
{
    text-align:right;
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

<h2>Domain Summary</h2>

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
<th>Total Topics</th>
<th>Used</th>
<th>Unused</th>
<th>Cards</th>
<th>Complete</th>
</tr>

<?php

$totalTopics = 0;
$totalUsed = 0;

foreach ($rows as $row):

    $totalTopics += $row['total_topics'];
    $totalUsed += $row['used_topics'];

?>


<tr>

<td><?= htmlspecialchars($row['domain']) ?></td>

<?php if ($row['total_topics'] == 0): ?>

<td class="numeric">&mdash;</td>
<td class="numeric">&mdash;</td>
<td class="numeric">&mdash;</td>
<td class="numeric">&mdash;</td>
<td class="numeric">N/A</td>

<?php else: ?>

<td class="numeric"><?= $row['total_topics'] ?></td>
<td class="numeric"><?= $row['used_topics'] ?></td>
<td class="numeric"><?= $row['unused_topics'] ?></td>
<td class="numeric"><?= $row['cards'] ?></td>
<td class="numeric"><?= $row['percent_complete'] ?>%</td>

<?php endif; ?>

</tr>

<?php endforeach; ?>

<tr>

<th>Total</th>
<th class="numeric"><?= $totalTopics ?></th>
<th class="numeric"><?= $totalUsed ?></th>
<th class="numeric"><?= $totalTopics - $totalUsed ?></th>
<th class="numeric">&mdash;</th>
<th class="numeric">

<?=
$totalTopics
?
round(100 * $totalUsed / $totalTopics,1)
:0
?>%

</th>

</tr>

</table>

<p class="footer">

Knowledge graph coverage by symbolic domain.

</p>

</body>

</html>
