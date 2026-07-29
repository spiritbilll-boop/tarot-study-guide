<?php
require_once __DIR__ . '/../../common/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $entity_id = isset($_POST['entity_id']) ? (int)$_POST['entity_id'] : 1;

    if (isset($_POST['add_topic'])) {

        $topic_id = (int)$_POST['topic_id'];

        $stmt = $conn->prepare(
            "INSERT IGNORE INTO entity_topics (entity_id, topic_id)
             VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $entity_id, $topic_id);

        if ($stmt->execute()) {
            $stmt2=$conn->prepare("SELECT name FROM topics WHERE id=?");$stmt2->bind_param("i",$topic_id);$stmt2->execute();$stmt2->bind_result($topic_name);$stmt2->fetch();$stmt2->close();$message="Added topic: ".$topic_name;
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['remove_topic'])) {

        $topic_id = (int)$_POST['topic_id'];

        $stmt = $conn->prepare(
            "DELETE FROM entity_topics
              WHERE entity_id = ?
                AND topic_id = ?"
        );
        $stmt->bind_param("ii", $entity_id, $topic_id);

        if ($stmt->execute()) {
            $stmt2=$conn->prepare("SELECT name FROM topics WHERE id=?");$stmt2->bind_param("i",$topic_id);$stmt2->execute();$stmt2->bind_result($topic_name);$stmt2->fetch();$stmt2->close();$message="Removed topic: ".$topic_name;
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$entity_id = isset($_GET['entity_id'])
    ? (int)$_GET['entity_id']
    : (isset($_POST['entity_id']) ? (int)$_POST['entity_id'] : 1);

$cards = [];
$r = $conn->query("SELECT id, card_name FROM tarot_cards ORDER BY id");
while ($row = $r->fetch_assoc()) {
    $cards[] = $row;
}

$currentTopics = [];
$stmt = $conn->prepare(
"SELECT t.id,
        t.name,
        COALESCE(d.name,'') AS domain_name
   FROM entity_topics et
   JOIN topics t
     ON t.id = et.topic_id
   LEFT JOIN domains d
     ON d.id = t.domain_id
  WHERE et.entity_id = ?
  ORDER BY d.name, t.name"
);
$stmt->bind_param("i", $entity_id);
$stmt->execute();
$res = $stmt->get_result();
while($row = $res->fetch_assoc()){
    $currentTopics[] = $row;
}
$stmt->close();
//$availableTopicCount = count($availableTopics);
$currentTopicCount = count($currentTopics);

$availableTopics = [];
$stmt = $conn->prepare(
"SELECT t.id,
        t.name,
        COALESCE(d.name,'') AS domain_name
   FROM topics t
   LEFT JOIN domains d
     ON d.id=t.domain_id
  WHERE t.enabled=1
    AND NOT EXISTS (
        SELECT 1
          FROM entity_topics et
         WHERE et.entity_id=?
           AND et.topic_id=t.id
    )
  ORDER BY d.name, t.name"
);
$stmt->bind_param("i",$entity_id);
$stmt->execute();
$res=$stmt->get_result();
while($row=$res->fetch_assoc()){
    $availableTopics[]=$row;
}
$availableTopicCount = count($availableTopics);
$stmt->close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Entity Topics Manager</title>
<link rel="stylesheet" href="../style.css">
<style>
body{font-family:Arial,sans-serif;margin:20px}
table{border-collapse:collapse;width:100%}
th,td{border:1px solid #bbb;padding:8px}
th{background:#eee}
.message{background:#efe;border:1px solid #8a8;padding:8px;margin:12px 0}
.section{margin-top:25px}
select{min-width:420px}
.inline{display:inline}
</style>
</head>
<body>

<h1>Entity Topics Manager</h1>
<p>
<a href="../">
Administration Home
</a>
</p>

<?php if($message!=''): ?>
<div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="get">
<p>
<label><strong>Tarot Card</strong></label><br><br>
<select name="entity_id" onchange="this.form.submit()">
<?php foreach($cards as $card): ?>
<option value="<?= $card['id'] ?>" <?= ($card['id']==$entity_id)?'selected':'' ?>>
<?= htmlspecialchars($card['id'].' - '.$card['card_name']) ?>
</option>
<?php endforeach; ?>
</select>
</p>
<noscript><input type="submit" value="Select"></noscript>
</form>

<div class="section">
<h2>Current Topics (<?= $currentTopicCount ?>)</h2>

<?php if(empty($currentTopics)): ?>
<p><em>No topics assigned.</em></p>
<?php else: ?>
<table>
<tr><th>Domain</th><th>Topic</th><th>Action</th></tr>
<?php foreach($currentTopics as $topic): ?>
<tr>
<td><?= htmlspecialchars($topic['domain_name']) ?></td>
<td><?= htmlspecialchars($topic['name']) ?></td>
<td>
<form method="post" class="inline">
<input type="hidden" name="entity_id" value="<?= $entity_id ?>">
<input type="hidden" name="topic_id" value="<?= $topic['id'] ?>">
<input type="submit" name="remove_topic" value="Remove"
onclick="return confirm('Remove topic?');">
</form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>

<div class="section">
<h2>Add Topic (<?= $availableTopicCount ?> available)</h2>

<form method="post">
<input type="hidden" name="entity_id" value="<?= $entity_id ?>">

<select name="topic_id">
<?php foreach($availableTopics as $topic): ?>
<option value="<?= $topic['id'] ?>">
<?= htmlspecialchars(($topic['domain_name'] ? $topic['domain_name'].' :: ' : '').$topic['name']) ?>
</option>
<?php endforeach; ?>
</select>

<input type="submit" name="add_topic" value="Add Topic">
</form>

</div>

</body>
</html>
