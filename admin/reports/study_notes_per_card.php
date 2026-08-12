<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    c.id,
    c.card_name,
    COUNT(n.id) AS note_count
FROM
    tarot_cards c
LEFT JOIN
    tarot_card_notes n
        ON n.card_id = c.id
GROUP BY
    c.id,
    c.card_name
ORDER BY
    c.id
    "
);

?>

<div class="admin-box">

<h2>

Study Notes per Card

</h2>

<table>

<tr>

<th>ID</th>

<th>Card</th>

<th>Study Notes</th>

</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td>

<?= $row["id"] ?>

</td>

<td>

<?= htmlspecialchars($row["card_name"]) ?>

</td>

<td>

<?= $row["note_count"] ?>

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
