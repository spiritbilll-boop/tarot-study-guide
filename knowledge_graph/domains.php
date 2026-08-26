<?php

require_once("../common/db.php");

$sql = "
SELECT
    d.id,
    d.name,
    COUNT(t.id) AS topics
FROM domains d
LEFT JOIN topics t
    ON t.domain_id = d.id
GROUP BY
    d.id,
    d.name
ORDER BY
    d.name
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Domains</title>

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

<h2>Domains</h2>

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
<th>Topics</th>
</tr>

<?php

while ($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<a href="topics.php?domain_id=<?php echo $row["id"]; ?>">

<?php
echo htmlspecialchars($row["name"]);
?>

</a>

</td>

<td class="numeric">

<?php
echo $row["topics"];
?>

</td>

</tr>

<?php

}

?>

</table>

<p class="footer">

Browse symbolic domains in the knowledge graph.

</p>

</body>

</html>
