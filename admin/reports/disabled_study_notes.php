<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    n.id,
    c.card_name,
    n.orientation,
    n.title
FROM
    tarot_card_notes n
JOIN
    tarot_cards c
        ON n.card_id = c.id
WHERE
    n.enabled = 0
ORDER BY
    c.id,
    n.orientation,
    n.sequence_no
    "
);

?>

<div class="admin-box">

<h2>

Disabled Study Notes

</h2>

<table>

<tr>

<th>ID</th>

<th>Card</th>

<th>Orientation</th>

<th>Title</th>

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

<?= $row["orientation"] == "U" ? "Upright" : "Reversed" ?>

</td>

<td>

<?= htmlspecialchars($row["title"] ?? "") ?>

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
