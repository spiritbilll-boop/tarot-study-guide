<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
    SELECT
        t.id,
        d.name AS domain_name,
        t.name,
        t.enabled
    FROM topics t
    JOIN domains d
        ON t.domain_id = d.id
    ORDER BY
        d.display_order,
        t.display_order,
        t.name
    "
);

?>

<div class="admin-box">

<h2>Topic Editor</h2>

<p>
<a class="button" href="edit.php">Add New Topic</a>
</p>

<table>

<tr>
    <th>Domain</th>
    <th>Topic</th>
    <th>Enabled</th>
    <th>Actions</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td><?= htmlspecialchars($row["domain_name"]) ?></td>

<td><?= htmlspecialchars($row["name"]) ?></td>

<td><?= $row["enabled"] ? "Yes" : "No" ?></td>

<td class="action-links">

<a href="edit.php?id=<?= $row["id"] ?>">Edit</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<?php

include(__DIR__ . "/../common/footer.php");

?>
