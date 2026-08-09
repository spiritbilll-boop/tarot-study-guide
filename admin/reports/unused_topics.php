<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    t.id,
    d.name AS domain_name,
    t.name
FROM
    topics t
LEFT JOIN
    domains d
        ON d.id = t.domain_id
LEFT JOIN
    entity_topics e
        ON e.topic_id = t.id
GROUP BY
    t.id,
    d.name,
    t.name
HAVING
    COUNT(e.topic_id) = 0
ORDER BY
    d.display_order,
    t.display_order,
    t.name
    "
);

?>

<div class="admin-box">

<h2>

Unused Topics

</h2>

<table>

<tr>

<th>ID</th>

<th>Domain</th>

<th>Topic</th>

<th>Action</th>

</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td>

<?= $row["id"] ?>

</td>

<td>

<?= htmlspecialchars($row["domain_name"]) ?>

</td>

<td>

<a href="../topic_editor/edit.php?id=<?= $row["id"] ?>">

<?= htmlspecialchars($row["name"]) ?>

</a>

</td>

<td class="action-links">

<a href="../topic_editor/edit.php?id=<?= $row["id"] ?>">

Edit

</a>

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
