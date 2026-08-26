<?php

require_once("../common/db.php");

$topic_id = isset($_GET["topic_id"])
  ? intval($_GET["topic_id"])
  : 0;

$sql = "
SELECT
    t.name AS topic_name,
    c.id,
    c.card_name
FROM topics t
JOIN entity_topics et
    ON et.topic_id = t.id
JOIN tarot_cards c
    ON c.id = et.entity_id
WHERE t.id = $topic_id
ORDER BY
    c.id
";

$result = mysqli_query($conn, $sql);
if (!$result)
{
    die(mysqli_error($conn));
}

$topic_name = "";

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Cards</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/theme.css">
<link rel="stylesheet" href="knowledge_graph.css">

<style>

table
{
    border-collapse:collapse;
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

<?php

while ($row = mysqli_fetch_assoc($result))
{

    if ($topic_name == "")
    {
        $topic_name = $row["topic_name"];

?>

<h2>

<?php
echo htmlspecialchars($topic_name);
?>

</h2>

<p>

<a href="javascript:history.back()">
Return to Topics
</a>

|

<a href="domains.php">
Domains
</a>

|

<a href="index.php">
Knowledge Graph Explorer
</a>

|

<a href="../index.php">
Main Menu
</a>

</p>

<table>

<tr>

<th>#</th>
<th>Card</th>

</tr>

<?php

    }

?>

<tr>

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

Tarot cards associated with this topic.

</p>

</body>

</html>
