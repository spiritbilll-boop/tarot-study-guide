<?php

require_once("../common/db.php");

$domain_id = isset($_GET["domain_id"])
    ? intval($_GET["domain_id"])
    : 0;

/*
 * Get the domain name.
 */

$domain_name = "Unknown Domain";

$sql = "
SELECT
    name
FROM domains
WHERE id = $domain_id
";

$result = mysqli_query($conn, $sql);

if ($result && ($row = mysqli_fetch_assoc($result)))
{
    $domain_name = $row["name"];
}

/*
 * Get the topics in this domain.
 */

$sql = "
SELECT
    id,
    name
FROM topics
WHERE domain_id = $domain_id
ORDER BY
    name
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Knowledge Graph Explorer - Topics</title>

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

<h2><?php echo htmlspecialchars($domain_name); ?></h2>

<p>

<a href="domains.php">
Return to Domains
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
<th>Topic</th>
</tr>

<?php

if ($result && mysqli_num_rows($result) > 0)
{
    while ($row = mysqli_fetch_assoc($result))
    {
?>

<tr>

<td>

<a href="topic.php?id=<?php echo $row["id"]; ?>">

<?php echo htmlspecialchars($row["name"]); ?>

</a>

</td>

</tr>

<?php

    }
}
else
{
?>

<tr>

<td>

<em>No topics found.</em>

</td>

</tr>

<?php

}

?>

</table>

<p class="footer">

Topics in this symbolic domain.

</p>

</body>

</html>
