<?php

require_once("../common/db.php");

$id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;

$sql = "
SELECT
    t.id,
    t.name AS topic_name,
    d.name AS domain_name,
    c.id AS card_id,
    c.card_name AS card_name
FROM topics t
JOIN domains d
    ON d.id = t.domain_id
LEFT JOIN entity_topics et
    ON et.topic_id = t.id
LEFT JOIN tarot_cards c
    ON c.id = et.entity_id
WHERE t.id = $id
ORDER BY
    c.id
";

$result =
    $conn->query($sql);

if (!$result)
{
    die(
        "Query failed: "
        .
        $conn->error
    );
}

if ($result->num_rows == 0)
{
    die("Topic not found.");
}

$row =
    $result->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title><?php echo htmlspecialchars($row["topic_name"]); ?></title>

<link
    rel="stylesheet"
    href="../style.css">

</head>

<body>

<h1>
<?php
echo htmlspecialchars(
    $row["topic_name"]
);
?>
</h1>

<p>

<b>Topic ID:</b>

<?php
echo $row["id"];
?>

<br>

<b>Domain:</b>

<?php
echo htmlspecialchars(
    $row["domain_name"]
);
?>

</p>

<h2>
Cards
</h2>

<table border="1" cellpadding="6">
<tr>
<th>ID</th>
<th>Card</th>
</tr>
<?php
do
{
    if (
        $row["card_id"]
    )
    {

?>
<tr>
<td>
<a href="../card.php?id=<?php echo $row["card_id"]; ?>">
<?php
echo htmlspecialchars(
    $row["card_id"]
);
?>
</a>
</td>
<td>
<a href="../card.php?id=<?php echo $row["card_id"]; ?>">
<?php
echo htmlspecialchars(
    $row["card_name"]
);
?>
</a>
</td>
</tr>

<?php
    }
}
while
(
    $row =
        $result->fetch_assoc()
);
?>
</table>

<p>

<a href="javascript:history.back()">

Return

</a>

|

<a href="index.php">

Knowledge Graph

</a>

</p>

</body>

</html>
