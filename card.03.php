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
$is_today = isset($_GET['today']);

/******************************************************************************
 *
 * Load Additional Study Notes
 *
 ******************************************************************************/

$stmt_notes = $mysqli->prepare("
    SELECT
        orientation,
        sequence_no,
        title,
        description,
        source
    FROM tarot_card_notes
    WHERE card_id = ?
      AND enabled = TRUE
    ORDER BY
        orientation,
        sequence_no
");

$stmt_notes->bind_param("i", $id);
$stmt_notes->execute();

$result_notes = $stmt_notes->get_result();

$upright_notes = [];
$reversed_notes = [];

while ($note = $result_notes->fetch_assoc())
{
    if ($note['orientation'] == 'U')
    {
        $upright_notes[] = $note;
    }
    else
    {
        $reversed_notes[] = $note;
    }
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
<?php if ($is_today): ?>

<div
style="
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:20px;
    margin:25px auto;
    max-width:700px;
    text-align:center;
">

<div
style="
    font-size:22px;
    color:#ffd966;
    font-weight:bold;
">

Tarot Card of the Day

</div>

<br>

<div
style="
    font-size:20px;
    color:#ffffff;
">

<?php
echo date('l, F j, Y');
?>

</div>

<br>

<div
style="
    font-size:42px;
    color:#ffd966;
    font-weight:bold;
">

<?php echo htmlspecialchars($card['card_name']); ?>

</div>

<br>

<div
style="
    font-size:18px;
    color:#dddddd;
    font-style:italic;
">

A single card selected for everyone today.
Take a few moments to reflect on how its
message may apply to your own journey.

</div>

</div>

<?php else: ?>

<h1>
<?php echo htmlspecialchars($card['card_name']); ?>
</h1>

<?php endif; ?>

<div class="nav">

<a class="nav-button" href="cards.php">
Return to Tarot Study Guide
</a>

<a class="nav-button" href="index.php">
Return to Tarot Reader
</a>

</div>

<div class="nav">

<?php if ($prev): ?>
<a class="nav-button"
   href="card.php?id=<?php echo $prev['id']; ?>">
&larr; Previous
</a>
<?php endif; ?>

<a class="nav-button"
   href="cards.php">
Card List
</a>

<?php if ($next): ?>
<a class="nav-button"
   href="card.php?id=<?php echo $next['id']; ?>">
Next &rarr;
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

<?php if (!empty($upright_notes)): ?>

<div class="section">

<h2>Additional Study Notes</h2>

<?php foreach ($upright_notes as $note): ?>

<h3>
<?php echo htmlspecialchars($note['title']); ?>
</h3>

<p>
<?php
echo nl2br(htmlspecialchars($note['description']));
?>
</p>

<?php if (!empty($note['source'])): ?>

<p>
<b>Source:</b>
<?php echo htmlspecialchars($note['source']); ?>
</p>

<?php endif; ?>

<hr>

<?php endforeach; ?>

</div>

<?php endif; ?>

<hr>

<?php endforeach; ?>

</div>

<?php endif; ?>

<div class="section">

<h2>Additional Study Notes</h2>

<?php foreach ($upright_notes as $note): ?>

<h3>
<?php echo htmlspecialchars($note['title']); ?>
</h3>

<p>

<?php
echo nl2br(htmlspecialchars($note['description']));
?>

</p>

<?php if (!empty($note['source'])): ?>

<p>

<b>Source:</b>

<?php echo htmlspecialchars($note['source']); ?>

</p>

<?php endif; ?>

<hr>

<?php endforeach; ?>

</div>

<?php endif; ?>
</div>

<div class="section">

<h2>Reversed Meaning</h2>

<?php
echo nl2br(htmlspecialchars($card['meaning_reversed']));
?>
<?php if (!empty($reversed_notes)): ?>

<div class="section">

<h2>Additional Study Notes</h2>

<?php foreach ($reversed_notes as $note): ?>

<h3>
<?php echo htmlspecialchars($note['title']); ?>
</h3>

<p>

<?php
echo nl2br(htmlspecialchars($note['description']));
?>

</p>

<?php if (!empty($note['source'])): ?>

<p>

<b>Source:</b>

<?php echo htmlspecialchars($note['source']); ?>

</p>

<?php endif; ?>

<hr>

<?php endforeach; ?>

</div>

<?php endif; ?>
</div>

<div class="nav">

<p style="text-align:center;">
Card <?php echo $card['id']; ?> of 78
</p>
<a class="nav-button" href="cards.php">
Return to Tarot Study Guide
</a>

<a class="nav-button" href="index.php">
Return to Tarot Reader
</a>

</div>

<div class="nav">

<?php if ($prev): ?>
<a class="nav-button"
   href="card.php?id=<?php echo $prev['id']; ?>">
&larr; Previous
</a>
<?php endif; ?>

<a class="nav-button"
   href="cards.php">
Card List
</a>

<?php if ($next): ?>
<a class="nav-button"
   href="card.php?id=<?php echo $next['id']; ?>">
Next &rarr;
</a>
<?php endif; ?>

</div>
</div>

</body>
</html>
