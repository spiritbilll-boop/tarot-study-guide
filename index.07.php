<?php
require_once 'auth.php';
require_once("study_notes/common.php");
// The rest of your application code goes safely right below here...
?>
<?php
// .tarot-card is where you change width of card container
// 1. DATABASE CONNECTION
$host = 'localhost';
$db   = 'tarot_db';
$user = 'tarot_db';
$pass = 'tarot_db';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Database connection failed: " . $e->getMessage());
}

// 2. CONFIGURING SPREADS
$spreads = [
    'single' => ['label' => 'Single Card (General Insight)', 'count' => 1],
    'three'  => ['label' => 'Three Cards (Past, Present, Future)', 'count' => 3],
    'five'   => ['label' => 'Five Cards (Situation, Obstacle, Past, Present, Future)', 'count' => 5],
    'Celtic Cross' => ['label' => 'Celtic Cross (Current Situation, Obstacle, Goal, Past, Near Future, The Querent (current state), The Querent (the Influences), The Environment, Hopes and Fears, Final Outcome)', 'count' => 10]
];
$position_labels = [

    'single' => [
        "Today's Guidance"
    ],

    'three' => [
        "Past",
        "Present",
        "Future"
    ],

    'five' => [
        "Situation",
        "Obstacle",
        "Past",
        "Present",
        "Future"
    ],

    'Celtic Cross' => [
        "Current Situation",
        "Obstacle",
        "Goal",
        "Past",
        "Near Future",
        "The Querent",
        "Influences",
        "Environment",
        "Hopes and Fears",
        "Final Outcome"
    ]

];

$reading_results = [];
$question = '';
$selected_spread = 'single';

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
    shuffle($deck);
    $drawn_ids = array_slice($deck, 0, $card_count);
    
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO tarot_readings (user_question, reading_type, card_ids) VALUES (?, ?, ?)");
    $stmt->execute([$question, $selected_spread, implode(',', $drawn_ids)]);
    
    // Fetch the drawn card details from our database
    // Using an array map to ensure all IDs are integers for security
    $in_clause = implode(',', array_fill(0, count($drawn_ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM tarot_cards WHERE id IN ($in_clause)");
    $stmt->execute($drawn_ids);
    $fetched_cards = $stmt->fetchAll();
    
    // Reorder fetched cards to match the original random draw sequence
    $cards_by_id = [];
    foreach ($fetched_cards as $card) {
        $cards_by_id[$card['id']] = $card;
    }
    
    foreach ($drawn_ids as $id) {
        if (isset($cards_by_id[$id])) {
            $card = $cards_by_id[$id];
            // 50% chance for the card to be reversed
            $card['is_reversed'] = (rand(0, 1) === 1); 
            $reading_results[] = $card;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tarot Card Reading</title>
<style>
  body { font-family: Times, serif; background: #1a1a2e; color: #fff; margin: 0 auto; padding: 20px; text-align: center; }
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

<a href="card.php?id=<?php echo rand(1,78); ?>"
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

   <p> This application is in beta testing.
Please continue to report any errors and give feedback to me. Thank you in advance. :-)
</p>

    <form method="POST" action="">
        <label for="question">Focus your mind and enter your question:</label>
        <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($question); ?>" required placeholder="e.g., What does my career path look like this month?">
        
        <label for="spread_type">Choose Spread:</label>
        <select id="spread_type" name="spread_type">
            <?php foreach ($spreads as $key => $info): ?>
                <option value="<?php echo $key; ?>" <?php echo ($selected_spread === $key) ? 'selected' : ''; ?>>
                    <?php echo $info['label']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
<center>
        <button type="submit" name="draw">Draw Deck</button>
</center>
    </form>

    <?php if (!empty($reading_results)): ?>
        <hr>
        <h2>Your Reading: <?php echo htmlspecialchars($spreads[$selected_spread]['label']); ?></h2>
        <p><strong>Your Question:</strong> "<?php echo htmlspecialchars($question); ?>"</p>
        
        <div class="card-container">
<?php foreach ($reading_results as $index => $card): ?>
    <div class="tarot-card">

<h3>

Position <?php echo $index + 1; ?>

<?php
if (isset($position_labels[$selected_spread][$index]))
{
    echo "<br>";
    echo "<span style=\"font-size:20px;color:#ffd966;\">";
    echo htmlspecialchars($position_labels[$selected_spread][$index]);
    echo "</span>";
}
?>

</h3>

        <img src="cards/<?php echo htmlspecialchars($card['image_file']); ?>"
             alt="<?php echo htmlspecialchars($card['card_name']); ?>"
             class="<?php echo $card['is_reversed'] ? 'reversed' : ''; ?>">
<p style="Times New Roman", Times, serif>
ID=<?php echo $card['id']; ?><br>
</p>

<h4>
    <?php echo htmlspecialchars($card['card_name']); ?>
    <?php echo $card['is_reversed'] ? '(Reversed)' : '(Upright)'; ?>
</h4>

<p style="font-size: 14px; text-align: left;">
<?php echo nl2br(htmlspecialchars(
    $card['is_reversed']
        ? $card['meaning_reversed']
        : $card['meaning_upright']
)); ?>
</p>

    </div>
            <?php endforeach; ?>
        </div>

<br>
<br><a href="/i-ching">Do an I Ching Consultation</a>
<br><a href="/">Go to the Main Page</a>
<center>
<img src=tarot.png>
</center>
    <h2 data-path-to-node="4">The Art of Inquiry: How to Consult the Tarot</h2>
    <p data-path-to-node="5">Welcome to this sacred space of reflection. The
      Tarot is not a tool for mere fortune-telling or a parlor trick to predict
      a rigid future. Instead, it acts as a cosmic mirror, reflecting the hidden
      dynamics, psychological currents, and shifting energies of your present
      situation.</p>
    <p data-path-to-node="6">To receive a clear answer from the Tarot, you must
      approach it with a clear mind. The quality of your insight depends
      entirely on the quality of your inquiry.</p>
    <h2 data-path-to-node="8">The Power of the Preliminary Pause</h2>
    <p data-path-to-node="9">Before you click "Draw Deck", I ask you to pause.</p>
    <p data-path-to-node="10">In our fast-paced digital world, our instinct is
      to react instantly, to demand immediate answers to chaotic thoughts. But
      the Tarot responds to stillness. Take three deep breaths. Ground yourself
      in the present moment. Clear away the immediate static of anxiety,
      frustration, or impatience.</p>
    <p data-path-to-node="11">Approach the tarot not as a passive spectator
      waiting to be told what to do, but as an active participant seeking
      wisdom. Treat this moment as a serious conversation with a wise, objective
      mentor.</p>
    <h2 data-path-to-node="13">Formulating Your Question</h2>
    <p data-path-to-node="14">Properly considering and phrasing your question is
      the most crucial part of the entire process. A vague, chaotic, or
      double-minded question will result in a confusing, fragmented card spray.</p>
    <p data-path-to-node="15">When formulating your question, follow these
      essential guidelines:</p>
    <h3 data-path-to-node="16">1. Avoid "Yes" or "No" Questions</h3>
    <p data-path-to-node="17">The Oracle speaks in nuances, cycles, and
      transformations. Questions like <i data-path-to-node="17" data-index-in-node="74">"Should
        I quit my job?"</i> or <i data-path-to-node="17" data-index-in-node="101">"Will
        I get back with my ex?"</i> force a binary choice onto a universe that
      operates in fluid waves.</p>
        <p>Instead of:</b> <i data-path-to-node="18,0,0" data-index-in-node="12">"Will
            my new business succeed?"</i></p>
        <p><b data-path-to-node="18,1,0" data-index-in-node="0">Try:</b>
          <i data-path-to-node="18,1,0" data-index-in-node="5">"What energies or
            obstacles should I expect if I pursue this new business venture?"</i></p>
    <h3 data-path-to-node="19">2. Focus on Your Agency (Own Your Position)</h3>
    <p data-path-to-node="20">You cannot control the actions of others, but you
      can control your own responses, attitude, and character. Frame your
      inquiry around your own path of right action.</p>
        <p data-path-to-node="21,0,0"><b data-path-to-node="21,0,0" data-index-in-node="0">Instead
            of:</b> <i data-path-to-node="21,0,0" data-index-in-node="12">"Why
            is my partner being so distant?"</i></p>
        <p data-path-to-node="21,1,0"><b data-path-to-node="21,1,0" data-index-in-node="0">Try:</b>
          <i data-path-to-node="21,1,0" data-index-in-node="5">"How can I best
            navigate the current distance in my relationship, and what is
            required of me right now?"</i></p>
    <h3 data-path-to-node="22">3. Seek Insight into the Present</h3>
    <p data-path-to-node="23">The future is not set in stone; it is born from
      the seeds of the present. Ask for clarity on <i data-path-to-node="23" data-index-in-node="93">what
        is happening right now</i> so you can make the wisest choices moving
      forward.</p>
        <p data-path-to-node="24,0,0"><b data-path-to-node="24,0,0" data-index-in-node="0">Great
            starting phrases include:</b></p>
            <p data-path-to-node="24,0,1,0,0"><i data-path-to-node="24,0,1,0,0"
                data-index-in-node="0">"What is the true nature of the situation
                regarding..."</i></p>
            <p data-path-to-node="24,0,1,1,0"><i data-path-to-node="24,0,1,1,0"
                data-index-in-node="0">"What do I need to understand about my
                current relationship with..."</i></p>
            <p data-path-to-node="24,0,1,2,0"><i data-path-to-node="24,0,1,2,0"
                data-index-in-node="0">"What is the wisest approach to take
                regarding..."</i></p>
    <h2 data-path-to-node="26">How to Proceed with Your Reading</h2>
        <p data-path-to-node="27,0,0"><b data-path-to-node="27,0,0" data-index-in-node="0">Write
            It Down:</b> Physically type or write your question out on a piece
          of paper. The act of writing forces your brain to crystallize your
          thoughts. If you cannot summarize your inquiry into one or two clear
          sentences, your mind is still too crowded. Simplify until it is pure.</p>
        <p data-path-to-node="27,1,0"><b data-path-to-node="27,1,0" data-index-in-node="0">Hold
            the Intent:</b> As you prepare to click/tap the "Draw Deck" button, hold the
          written question in your mind's eye. Visualize the people, choices, or
          feelings involved. Let your intent fill the space.</p>
        <p data-path-to-node="27,2,0"><b data-path-to-node="27,2,0" data-index-in-node="0">Draw
            the Deck:</b> Click/tap the button to see and read the result.</p>
        <p data-path-to-node="27,3,0"><b data-path-to-node="27,3,0" data-index-in-node="0">Contemplate
            the Cards:</b>&nbsp; Do not rush. Sit with what you see and read for
          a moment. Let it settle into your intuition.</p>
    <p data-path-to-node="28">Remember, the Tarot does not strip away your free
      will; it illuminates it. Use the wisdom generated here to cultivate
      patience when the oracle advises waiting, and to find courage when it
      signals that the time has come to cross the great water.</p>
    <p>Disclaimer: The views and experiences shared in this tool reflect
      personal perspectives on spirituality, self-discovery, and personal
      growth. They are offered for informational and inspirational purposes only
      and should not be considered professional, medical, psychological, legal,
      or financial advice. Each individual's path is unique, and readers are
      encouraged to exercise their own discernment and seek appropriate
      professional guidance when necessary.
    </p>
<?php 
  echo ".<br>Last Modified (on the server side): ".strftime('%c',filemtime($_SERVER['SCRIPT_FILENAME'])) . " GMT<br>";
?>


    <?php endif; ?>

<div style="margin-top: 40px; margin-bottom: 20px; text-align: center;">
        <hr style="border: 0; border-top: 1px solid #162447; margin-bottom: 20px;">
        <a href="/i-ching" class="nav-link">Do an I Ching Consultation</a>
        <span style="color: #666; margin: 0 15px;">|</span>
        <a href="/" class="nav-link">Go to the main page of the website</a>
    </div>

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
</body>
</html>
