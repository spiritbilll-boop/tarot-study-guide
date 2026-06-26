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

$prev = null;
$next = null;

$prev_id = $card['id'] - 1;
$next_id = $card['id'] + 1;

if ($prev_id >= 1)
{
    $stmt_prev = $mysqli->prepare("
        SELECT id, card_name
        FROM tarot_cards
        WHERE id = ?
    ");

    $stmt_prev->bind_param("i", $prev_id);
    $stmt_prev->execute();

    $prev = $stmt_prev->get_result()->fetch_assoc();
}

if ($next_id <= 78)
{
    $stmt_next = $mysqli->prepare("
        SELECT id, card_name
        FROM tarot_cards
        WHERE id = ?
    ");

    $stmt_next->bind_param("i", $next_id);
    $stmt_next->execute();

    $next = $stmt_next->get_result()->fetch_assoc();
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
    background-color: #555555;
    color: white;
    font-family: "Times New Roman", Times, serif;
    line-height: 1.5;
    margin: 20px;
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

h1
{
    font-size: 48px;
    text-align: center;
}

h2
{
    font-size: 32px;
    text-align: center;
}

a
{
    color: white;
}

.nav
{
    text-align: center;
    margin-top: 20px;
}
.nav-button
{
    display:inline-block;
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:10px 20px;
    margin:5px;
    color:white;
    text-decoration:none;
    font-weight:bold;
    min-width:130px;
    text-align:center;
}

.nav-button:hover
{
    background:#e43f5a;
}

</style>

</head>

<body>

<div class="container">

<h1>
<?php echo htmlspecialchars($card['card_name']); ?>
</h1>
<div class="nav">
<br>
<a href="cards.php">
Return to Tarot Study Guide
</a>
<br>
<a href="index.php">
Return to Tarot Reader
</a>
</div>
<div class="nav">
<?php if ($prev): ?>
<a href="card.php?id=<?php echo $prev['id']; ?>">
<--<?php echo htmlspecialchars($prev['card_name']); ?>
</a>
<?php endif; ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="cards.php">
Card List
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($next): ?>
<a href="card.php?id=<?php echo $next['id']; ?>">
<?php echo htmlspecialchars($next['card_name']); ?>-->
</a>
<?php endif; ?>
</div>


<p style="text-align:center;">
Card <?php echo $card['id']; ?> of 78
</p>

<img
    src="cards/<?php echo htmlspecialchars($card['image_file']); ?>"
    alt="<?php echo htmlspecialchars($card['card_name']); ?>"
>

<div class="section">

<h2>Upright Meaning</h2>

<?php
echo nl2br(htmlspecialchars($card['meaning_upright']));
?>

</div>

<div class="section">

<h2>Reversed Meaning</h2>

<?php
echo nl2br(htmlspecialchars($card['meaning_reversed']));
?>
</div>

<div class="nav">
<a href="cards.php">
Return to Tarot Study Guide
</a>
<br>
<a href="index.php">
Return to Tarot Reader
<br>
</a>
<?php if ($prev): ?>
<a href="card.php?id=<?php echo $prev['id']; ?>">
<--<?php echo htmlspecialchars($prev['card_name']); ?>
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php endif; ?>
<a href="cards.php">
Card List
</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($next): ?>
<a href="card.php?id=<?php echo $next['id']; ?>">
<?php echo htmlspecialchars($next['card_name']); ?>-->
</a>
<?php endif; ?>
</div>

</body>
</html>
