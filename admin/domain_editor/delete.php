<?php

require_once(__DIR__ . "/../../common/db.php");

$id =
    isset($_GET["id"])
        ? intval($_GET["id"])
        : 0;

if ($id > 0)
{
    $stmt = mysqli_prepare(
        $conn,
        "
        DELETE
        FROM domains
        WHERE id = ?
        "
    );

    if (!$stmt)
    {
        die(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

header("Location: index.php");

exit;
