<?php

require_once __DIR__ . '/common/db.php';

/*
------------------------------------------------------------
Validate card id
------------------------------------------------------------
*/

$card_id =
    isset($_GET['id'])
        ? intval($_GET['id'])
        : 1;

if ($card_id < 1)
{
    $card_id = 1;
}

/*
------------------------------------------------------------
Load Tarot card
------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT card_name
     FROM tarot_cards
     WHERE id = ?"
);

if (!$stmt)
{
    die($conn->error);
}

$stmt->bind_param("i", $card_id);
$stmt->execute();

$result = $stmt->get_result();
$card = $result->fetch_assoc();

$stmt->close();

if (!$card)
{
    die("Unknown card.");
}

/*
------------------------------------------------------------
Load Associated Knowledge
------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        d.name AS domain_name,
        t.name AS topic_name,
        t.description AS topic_description
     FROM entity_topics et
     JOIN topics t
        ON t.id = et.topic_id
     JOIN domains d
        ON d.id = t.domain_id
     WHERE et.entity_id = ?
       AND t.enabled = 1
       AND d.enabled = 1
     ORDER BY
        d.name,
        t.display_order,
        t.name"
);

if (!$stmt)
{
    die($conn->error);
}

$stmt->bind_param("i", $card_id);
$stmt->execute();

$result = $stmt->get_result();

$topics = [];

while ($row = $result->fetch_assoc())
{
    $topics[] = $row;
}

$stmt->close();

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>

<?php
 echo htmlspecialchars($card['card_name']);
?>
 - Associated Knowledge

</title>

<style>

body
{
    background:#1d3557;
    color:white;
    font-family:Arial,Helvetica,sans-serif;
    margin:30px;
}

h1
{
    text-align:center;
    color:#ffd966;
}

h2
{
    color:#ffd966;
}

.panel
{
    background:white;
    color:#222;
    border-radius:10px;
    padding:20px;
    margin-top:25px;
    margin-bottom:25px;
}

.back-link
{
    color:#1d3557;
    text-decoration:underline;
    font-weight:bold;
}

.back-link:hover
{
    text-decoration:underline;
}

.topic
{
    margin-bottom:25px;
}

.domain
{
    font-size:22px;
    font-weight:bold;
    color:#1d3557;
    margin-bottom:8px;
}

.topic-name
{
    font-size:20px;
    font-weight:bold;
    margin-bottom:8px;
}

.topic-description
{
    line-height:1.6;
}

.button
{
    display:inline-block;
    background:#162447;
    border:2px solid #e43f5a;
    border-radius:8px;
    padding:12px 24px;
    color:white;
    text-decoration:none;
    font-size:18px;
    font-weight:bold;
}

.button:hover
{
    background:#24365d;
}

</style>

</head>

<body>

<p>

<a
class="back-link"
href="card.php?id=<?php echo $card_id; ?>">

&larr; Return to Card

</a>

</p>

<h1>

<?php
 echo htmlspecialchars($card['card_name']);
?>

</h1>

<div class="panel">

<h2>

Associated Knowledge

</h2>

<?php

if (count($topics) == 0)
{

?>

<p>

No enabled Topics are currently associated with this card.

</p>

<?php

}
else
{

    $current_domain = '';

    foreach ($topics as $topic)
    {

        if ($current_domain !== $topic['domain_name'])
        {

            $current_domain = $topic['domain_name'];

?>

<div class="domain">

<?php
 echo htmlspecialchars($current_domain);
?>

</div>

<?php

        }

?>

<div class="topic">

<div class="topic-name">

<?php
 echo htmlspecialchars($topic['topic_name']);
?>

</div>

<?php

        if (!empty($topic['topic_description']))
        {

?>

<div class="topic-description">

<?php
 echo nl2br(htmlspecialchars($topic['topic_description']));
?>

</div>

<?php

        }

?>

</div>

<?php

    }

}

?>

</div>

<p style="text-align:center;">

<a
class="button"
href="card.php?id=<?php echo $card_id; ?>">

Back to Card

</a>

</p>

</body>

</html>
