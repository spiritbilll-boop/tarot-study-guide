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

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

if ($search !== '')
{
    $stmt = $mysqli->prepare("
        SELECT
            id,
            card_name
        FROM tarot_cards
        WHERE card_name LIKE ?
        ORDER BY id
    ");

    $term = '%' . $search . '%';

    $stmt->bind_param("s", $term);

    $stmt->execute();

    $result = $stmt->get_result();
}
elseif ($category !== '')
{
    switch ($category)
    {
        case 'major':

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                WHERE image_file LIKE 'm%'
                ORDER BY id
            ");

            break;

        case 'cups':

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                WHERE image_file LIKE 'c%'
                ORDER BY id
            ");

            break;

        case 'wands':

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                WHERE image_file LIKE 'w%'
                ORDER BY id
            ");

            break;

        case 'swords':

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                WHERE image_file LIKE 's%'
                ORDER BY id
            ");

            break;

        case 'pentacles':

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                WHERE image_file LIKE 'p%'
                ORDER BY id
            ");

            break;

        default:

            $result = $mysqli->query("
                SELECT id, card_name
                FROM tarot_cards
                ORDER BY id
            ");
    }
}
else
{
    $result = $mysqli->query("
        SELECT id, card_name
        FROM tarot_cards
        ORDER BY id
    ");
}

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
    font-family: "Times New Roman", Times, serif;
    margin: 20px;
}

h1
{
    text-align: center;
}

.card-list
{
    max-width: 700px;
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

.button
{
    display: inline-block;
    padding: 10px 20px;
    margin: 10px;
    background: #162447;
    border: 1px solid #e43f5a;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}

.button:hover
{
    background: #e43f5a;
}

</style>

</head>

<body>

<h1>Tarot Study Guide</h1>
<div style="text-align:center; margin-bottom:20px;">

<a class="button" href="cards.php">All Cards</a>

<a class="button" href="cards.php?category=major">
Major Arcana
</a>

<a class="button" href="cards.php?category=cups">
Cups
</a>

<a class="button" href="cards.php?category=wands">
Wands
</a>

<a class="button" href="cards.php?category=swords">
Swords
</a>

<a class="button" href="cards.php?category=pentacles">
Pentacles
</a>

</div>

<form method="get" style="text-align:center; margin-bottom:20px;">

    <input
        type="text"
        name="search"
        value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Search cards..."
        style="padding:8px; width:300px;">

    <button type="submit">
        Search
    </button>

</form>

<div class="footer">
<br>
<a href="index.php" style="color:white;">
Return to Tarot Reader
</a>
<br><br>
<a href="/" style="color:white;">
Return to Main Page
</a>
</div>

<p align="center">
<a class="button" href="card.php?id=<?php echo rand(1,78); ?>">
Random Card Study
</a>
</p>

<div class="card-list">

<?php

while ($row = $result->fetch_assoc())
{
    echo '<a class="card-link" href="card.php?id=' .
         $row['id'] .
         '">' .
         htmlspecialchars($row['card_name']) .
         '</a>';
}

?>

</div>


</body>
</html>
