<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$result = mysqli_query(
    $conn,
    "
    SELECT
        id,
        name,
        enabled
    FROM
        domains
    ORDER BY
        display_order,
        name
    "
);

?>

<div class="admin-box">

<h2>Domain Editor</h2>

<p>
<a class="button" href="edit.php">Add New Domain</a>
</p>

<table>

<tr>
    <th>Domain</th>
    <th>Enabled</th>
    <th>Actions</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<tr>

<td><?= htmlspecialchars($row["name"]) ?></td>

<td><?= $row["enabled"] ? "Yes" : "No" ?></td>

<td class="action-links">

<a href="edit.php?id=<?= $row["id"] ?>">Edit</a>

<a href="delete.php?id=<?= $row["id"] ?>"
   onclick="return confirm('Delete this domain?');">
    Delete
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

<?php

include(__DIR__ . "/../common/footer.php");

?>
