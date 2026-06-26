<?php

$mysqli = new mysqli(
    "localhost",
    "tarot_db",
    "tarot_db",
    "tarot_db"
);

if ($mysqli->connect_error)
{
    die("Database connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("
    SELECT
        id,
        card_name
    FROM tarot_cards
    ORDER BY id
");

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title>Tarot Study Guide</title>

<style>

body
{
    background-color: #555555;
    color: white;
    font-family: sans-serif;
    margin: 20px;
}

h1
{
    text-align: center;
}

.card-list
{
    max-width: 600px;
    margin: auto;
}

.card-link
{
    display: block;
    padding: 8px;
    margin: 3px 0;
    background: #162447;
    border: 1px solid #e43f5a;
    border-radius: 4px;
    color: white;
    text-decoration: none;
}

.card-link:hover
{
    background: #e43f5a;
}

.footer
{
    margin-top: 30px;
    text-align: center;
}

</style>

</head>

<body>

<h1>Tarot Study Guide</h1>

<p align="center">
Browse all 78 cards of the Tarot deck.
</p>

<div class="card-list">

<?php

while ($row = $result->fetch_assoc())
{
    echo '<a class="card-link" href="card.php?id=' .
         $row['id'] .
         '">'
         . htmlspecialchars($row['card_name']) .
         '</a>';
}

?>

</div>

<div class="footer">

<br>

<a href="index.php">
Return to Tarot Reader
</a>

<br><br>

<a href="/" style="color:white;">
Return to Main Page
</a>

</div>

</body>
</html>
