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

$id = intval($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("
    SELECT
        id,
        card_name,
        image_file,
        meaning_upright,
        meaning_reversed
    FROM tarot_cards
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$card = $result->fetch_assoc();

if (!$card)
{
    die("Card not found.");
}

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title><?php echo htmlspecialchars($card['card_name']); ?></title>

<style>

body
{
    font-family: "Times New Roman", Times, serif;
    line-height: 1.5;
color: white;
}

h1
{
    font-size: 48px;
}

h2
{
    font-size: 32px;
}

body
{
    font-family: "Times New Roman", Times, serif;
    line-height: 1.5;
}

.container
{
    max-width: 1000px;
    margin: auto;
}

img
{
    max-width: 400px;
    display: block;
    margin: auto;
}

.section
{
    background: #162447;
    border: 1px solid #e43f5a;
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
}

a
{
    color: white;
}

h1, h2
{
    text-align: center;
}

</style>

</head>

<body>

<div class="container">

<h1>
<?php echo htmlspecialchars($card['card_name']); ?>
</h1>

<img
    src="cards/<?php echo htmlspecialchars($card['image_file']); ?>"
    alt="<?php echo htmlspecialchars($card['card_name']); ?>"
>

<div class="section">

<h2>Upright Meaning</h2>

ID=<?php echo $card['id']; ?><br>
<?php
echo nl2br(htmlspecialchars($card['meaning_upright']));
?>

<p style="Times New Roman", Times, serif>
</div>

<div class="section">

<h2>Reversed Meaning</h2>

<?php
echo nl2br(htmlspecialchars($card['meaning_reversed']));
?>

</div>

<br>

<a href="cards.php">
Back to Card List
</a>

<br><br>

<a href="index.php">
Return to Tarot Reader
</a>

</div>

</body>
</html>
