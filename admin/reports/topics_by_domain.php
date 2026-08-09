<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    d.name AS domain_name,
    t.id,
    t.name AS topic_name,
    t.enabled
FROM
    domains d
LEFT JOIN
    topics t
        ON t.domain_id = d.id
ORDER BY
    d.display_order,
    d.name,
    t.display_order,
    t.name
    "
);

?>

<div class="admin-box">

<h2>

Topics by Domain

</h2>

<table>

<tr>

<th>Domain</th>

<th>Topic</th>

<th>Enabled</th>

</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td>

<?= htmlspecialchars($row["domain_name"]) ?>

</td>

<td>

<?php if ($row["id"] !== null) : ?>

<a href="../topic_editor/edit.php?id=<?= $row["id"] ?>">

<?= htmlspecialchars($row["topic_name"]) ?>

</a>

<?php else : ?>

<em>No topics</em>

<?php endif; ?>

</td>

<td>

<?php if ($row["id"] !== null) : ?>

<?= $row["enabled"] ? "Yes" : "No" ?>

<?php else : ?>

?

<?php endif; ?>

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
