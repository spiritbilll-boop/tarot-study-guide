<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
SELECT
    d.id,
    d.name
FROM
    domains d
LEFT JOIN
    topics t
        ON t.domain_id = d.id
WHERE
    t.id IS NULL
ORDER BY
    d.display_order,
    d.name
    "
);

?>

<div class="admin-box">

<h2>

Unused Domains

</h2>

<table>

<tr>

<th>ID</th>

<th>Domain</th>

<th>Action</th>

</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td>

<?= $row["id"] ?>

</td>

<td>

<a href="../domain_editor/edit.php?id=<?= $row["id"] ?>">

<?= htmlspecialchars($row["name"]) ?>

</a>

</td>

<td class="action-links">

<a href="../domain_editor/edit.php?id=<?= $row["id"] ?>">

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
