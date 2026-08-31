<?php
//require_once 'auth.php';
require_once(__DIR__ . "/admin/study_notes/common.php");
// The rest of your application code goes safely right below here...
// mysqli, NOT PDO
require_once __DIR__ . '/common/db.php';

// 2. CONFIGURING SPREADS FROM THE DATABASE

$spreads = [];
$position_labels = [];

$spread_sql = "
SELECT
    s.id,
    s.spread_key,
    s.spread_name,
    COUNT(sd.id) AS card_count
FROM spreads s
INNER JOIN spread_details sd
    ON sd.spread_id = s.id
GROUP BY
    s.id,
    s.spread_key,
    s.spread_name
ORDER BY
    s.id
";

$spread_result = $conn->query($spread_sql);

if ($spread_result)
{
    while ($spread = $spread_result->fetch_assoc())
    {
        $spreads[$spread['spread_key']] = [
            'label' => $spread['spread_name'],
            'count' => (int)$spread['card_count']
        ];
    }
}

$position_sql = "
SELECT
    s.spread_key,
    sd.position_number,
    sd.position_name,
    sd.position_meaning
FROM spreads s
INNER JOIN spread_details sd
    ON sd.spread_id = s.id
ORDER BY
    s.id,
    sd.position_number
";

$position_result = $conn->query($position_sql);

if ($position_result)
{
    while ($position = $position_result->fetch_assoc())
    {
        $position_labels[$position['spread_key']][] = [
            'label' => $position['position_name'],
            'meaning' => $position['position_meaning']
        ];
    }
}

$reading_results = [];
$question = '';
$selected_spread = 'single';
$randomizer = new \Random\Randomizer();

// 3. HANDLE USER DRAW REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw'])) {
$question = trim($_POST['question'] ?? 'No question asked.');
$selected_spread = $_POST['spread_type'] ?? 'single';

if (!array_key_exists($selected_spread, $spreads)) {
$selected_spread = 'single';
}

$card_count = $spreads[$selected_spread]['count'];

// Generate an array from 1 to 78, shuffle them, and pick the required amount
$deck = range(1, 78);
$deck = $randomizer->shuffleArray($deck);
$drawn_ids = array_slice($deck, 0, $card_count);

// Fetch the drawn card details from our database
// Using an array map to ensure all IDs are integers for security
$in_clause = implode(',', array_fill(0, count($drawn_ids), '?'));

$stmt = $conn->prepare(
"SELECT * FROM tarot_cards WHERE id IN ($in_clause)"
);

$types = str_repeat('i', count($drawn_ids));

$stmt->bind_param(
$types,
...$drawn_ids
);

$stmt->execute();

$result = $stmt->get_result();

$fetched_cards = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Reorder fetched cards to match the original random draw sequence
$cards_by_id = [];
foreach ($fetched_cards as $card) {
$cards_by_id[$card['id']] = $card;
}

foreach ($drawn_ids as $id) {
if (isset($cards_by_id[$id])) {
    $card = $cards_by_id[$id];
    // 50% chance for the card to be reversed
    $card['is_reversed'] = ($randomizer->getInt(0, 1) === 1);
$orientation_codes[] =
$card['is_reversed']
? 'R'
: 'U';
    $reading_results[] = $card;
}
}
$card_orientations =
implode(
',',
$orientation_codes
);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tarot Card Reading</title>
<style>
body { font-family: Times, serif; background: #1a1a2e; color: #fff; margin: 0 auto; padding: 20px;  text-align: center;}
.card-container {
display: flex;
flex-wrap: wrap;
justify-content: center;
gap: 20px;
margin-top: 30px;
}

.tarot-card {
background: #4264B9;
border: 2px solid #e43f5a;
padding: 15px;
border-radius: 8px;
}
.tarot-card img { width: 600px; border-radius: 4px; }
.reversed { transform: rotate(180deg); }
form { background: #162447; padding: 20px; border-radius: 8px; display: inline-block; text-align: left; margin-bottom: 20px; }
input[type="text"], select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 4px; border: none; }
button { background: #1a1a2e; color: teal; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 24px; }
button:hover { background: #ff4a68; }
.card-link
{
color:#1d3557;

text-decoration:none;

font-weight:bold;
}
.knowledge-graph-link
{
    color: #ffd966;
    font-weight: bold;
    text-decoration: underline;
}

.knowledge-graph-link:hover
{
    color: #ffe699;
}

.card-link:hover
{
color:#4b6cb7;

text-decoration:underline;
}
</style>
</head>
<body>

<h1>Tarot Card Reading</h1>

<div style="margin:20px 0;text-align:center;">

    <a href="cards.php"
       style="
            display:inline-block;
            background:#162447;
            border:2px solid #e43f5a;
            border-radius:8px;
            padding:12px 24px;
            color:white;
            text-decoration:none;
            font-size:20px;
            font-weight:bold;
            margin-right:15px;
       ">
       Tarot Study Guide
    </a>

<a href="card.php?id=<?php echo $randomizer->getInt(1,78); ?>"
   style="
        display:inline-block;
        background:#162447;
        border:2px solid #e43f5a;
        border-radius:8px;
        padding:12px 24px;
        color:white;
        text-decoration:none;
        font-size:20px;
        font-weight:bold;
        margin-right:15px;
   ">
    Random Card Study
</a>
<a href="today.php"
   style="
        display:inline-block;
        background:#162447;
        border:2px solid #e43f5a;
        border-radius:8px;
        padding:12px 24px;
        color:white;
        text-decoration:none;
        font-size:20px;
        font-weight:bold;
   ">
   Card of the Day
</a>
</div>

<?php
  $currentDateTime = new DateTime('now');
  $currentDate = $currentDateTime->format('l, F j, Y H:i:s');
  echo "The time is " . $currentDate . " GMT";
?>

<center>
<table border=1>
<tr>
  <th>Section of the Tarot Deck</th><th>Card Number range</th>
</tr>
<tr>
<tr>
  <td><a class="nav-link" href="/tarot-study-guide/cards.php?category=major" target=_blank>Major Arcana in Fool-to-World order</a></td><td>57-78</td>
</tr>
</tr>
<tr>
  <td><a class="nav-link" href="/tarot-study-guide/cards.php?category=cups" target=_blank>Cups (Alchemical element Water)</a></td><td>1-14</td>
</tr>
  <td><a class="nav-link" href="/tarot-study-guide/cards.php?category=wands" target=_blank>Wands, Ace through King (Alchemical element Fire)</a></td><td>15-28</td>
<tr>
  <td><a class="nav-link" href="/tarot-study-guide/cards.php?category=swords" target=_blank>Swords (Alchemical element Air)</a></td><td>29-42</td>
</tr>
  <td><a class="nav-link" href="/tarot-study-guide/cards.php?category=pentacles" target=_blank>Pentacles (Alchemical element Earth)</a></td><td>43-56</td>
</table>
</center>
<h2>Documentation</h2>
<p><a class="nav-link" href="/docs/tarot_overview.html" target=_blank>Tarot Deck: High-Level Outline</a></p>
<p><a class="nav-link" href="/docs/tarot_db.png" target=_blank>For RDBMS geeks: Entity-Relationship (ER) diagram</a></p>
<br>
    <form method="POST" action="">
<center>
        <label for="question">Focus your mind and enter your question:</label>
        <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($question); ?>" required placeholder="Enter your question or meditation">
        
        <label for="spread_type">Choose Spread:</label>
        <select id="spread_type" name="spread_type">
            <?php foreach ($spreads as $key => $info): ?>
                <option value="<?php echo $key; ?>" <?php echo ($selected_spread === $key) ? 'selected' : ''; ?>>
                    <?php echo $info['label']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" name="draw">Draw Deck</button>
    </form>

    <?php if (!empty($reading_results)): ?>
        <hr>
        <p><strong>Your Question:</strong> "<?php echo htmlspecialchars($question); ?>"</p>
        <div class="card-container">
<?php foreach ($reading_results as $index => $card): ?>
    <div class="tarot-card">

<h3>

Position <?php echo $index + 1; ?>

<?php
if (isset($position_labels[$selected_spread][$index]))
{
    $position = $position_labels[$selected_spread][$index];

    echo "<br>";
    echo "<span style=\"font-size:20px;color:#ffd966;font-weight:bold;\">";
    echo htmlspecialchars($position['label']);
    echo "</span>";

    echo "<div style=\"font-size:16px;color:#ffffff;font-weight:normal;font-style:italic;line-height:1.5;margin:10px 15px 0 15px;\">";
    echo htmlspecialchars($position['meaning']);
    echo "</div>";
}
?>

</h3>

<img src="cards/<?php echo htmlspecialchars($card['image_file']); ?>"
alt="<?php echo htmlspecialchars($card['card_name']); ?>"
class="<?php echo $card['is_reversed'] ? 'reversed' : ''; ?>" >

<p style="Times New Roman", Times, serif>
ID=<?php echo $card['id']; ?><br>
</p>

<h4>

<a
class="card-link"
href="card.php?id=<?php echo $card['id']; ?>">

<?php echo htmlspecialchars($card['card_name']); ?>

</a>

<?php echo $card['is_reversed'] ? ' (Reversed)' : ' (Upright)'; ?>

</h4>

<p style="margin: 0 0 15px 0;">

<a
class="knowledge-graph-link"
href="knowledge_graph/card.php?id=<?php echo $card['id']; ?>">

Knowledge Graph

</a>

</p>

<p style="margin: 0 0 15px 0;">

<a
class="knowledge-graph-link"
href="knowledge_graph/discover_similar_cards.php?id=<?php echo $card['id']; ?>">

Discover Similar Cards

</a>

</p>
</h4>

<?php

$meaning =
    $card['is_reversed']
        ? $card['meaning_reversed']
        : $card['meaning_upright'];

if (!empty($meaning))
{
?>

<div style="
    margin-top:15px;
    margin-bottom:20px;
    padding:15px;
    background:#eef4ff;
    border-left:6px solid #4b6cb7;
    border-radius:8px;
    text-align:left;
">

<strong style="color:#000;">

Card Meaning

</strong>

<p style="
    color:#222;
    font-size:18px;
    line-height:1.6;
    margin-top:10px;
">

<?php echo nl2br(h($meaning)); ?>

</p>

</div>

<?php
}
?>

<?php
$study_notes =
    get_enabled_notes_for_card(
        $conn,
        $card['id'],
        $card['is_reversed']
            ? 'R'
            : 'U'
    );

if (!empty($study_notes))
{
?>
<div style="height:20px;"></div><hr>

<h3 style="
    margin-top:30px;
    margin-bottom:20px;
    text-align:center;
    font-size:28px;
    letter-spacing:1px;
">
    Study Notes
</h3>

<?php
foreach ($study_notes as $note)
{
?>

<div style="
    background:#fdfdfd;
    color:#222;
    border:1px solid #d6d6d6;
    border-left:6px solid #4b6cb7;
    border-radius:10px;
    padding:18px;
    margin:20px 0;
    box-shadow:0 2px 8px rgba(0,0,0,.15);
    text-align:left;
">

<div style="
    font-size:22px;
    font-weight:bold;
    margin-bottom:12px;
">
<?php echo h($note['title']); ?>
</div>
<div style="height:10px;"></div>

<?php
if (!empty($note['description']))
{
?>
<div style="margin-bottom:12px;">
<em>
<?php echo nl2br(h($note['description'])); ?>
</em>
</div>
<?php
}
?>
<div style="
    margin-top:18px;
    padding-top:10px;
    border-top:1px solid #ddd;
    text-align:right;
    font-size:14px;
    color:#666;
">
    <em>Source:</em>
    <?php echo h($note['source']); ?>

</div>

<?php
}
}
?>
    </div>
            <?php endforeach; ?>
        </div>
<center>
<?php 
  echo ".<br>Last Modified (on the server side): ".strftime('%c',filemtime($_SERVER['SCRIPT_FILENAME'])) . " GMT<br>";
?>


    <?php endif; ?>

<style>
  .nav-link {
     color: #e43f5a;
     text-decoration: none;
     font-size: 16px;
     font-weight: bold;
     transition: color 0.2s ease;
   }
   .nav-link:hover {
     color: #ff4a68;
     text-decoration: underline;
     }
</style>

<div style="margin-top: 40px; margin-bottom: 20px;">
  <p><a href="/i-ching"><font color=ffff00>Do an I Ching Consultation</a></p>
  <p><a href="/"><font color=ffff00>Go to the Main Page</a></p>
</div>
</body>
</html>
