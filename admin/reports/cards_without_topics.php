<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    c.id,
    c.card_name
FROM
    tarot_cards c
LEFT JOIN
    entity_topics e
        ON e.entity_id = c.id
GROUP BY
    c.id,
    c.card_name
HAVING
    COUNT(e.topic_id) = 0
ORDER BY
    c.id
    "
);

?>

<div class="admin-box">

<h2>

Cards with No Topics

</h2>

<table>

<tr>

<th>ID</th>

<th>Card</th>

</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td>

<?= $row["id"] ?>

</td>

<td>

<?= htmlspecialchars($row["card_name"]) ?>

</td>

</tr>

<?php endwhile; ?>

</table>

<p>

<a
class="button"
href="index.php">

Back to Reports

</a>

</p>

</div>

<?php

include(__DIR__ . "/../common/footer.php");

?>
