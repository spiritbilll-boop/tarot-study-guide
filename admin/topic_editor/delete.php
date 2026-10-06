<?php

require_once("../../common/db.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id > 0)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT COUNT(*)
         FROM entity_topics
         WHERE topic_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $usage_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($usage_count > 0)
    {
        die(
            "Cannot delete this topic. It is currently assigned to "
            . $usage_count
            . " card(s). Remove those card/topic associations first."
        );
    }

    mysqli_query(
        $conn,
        "DELETE FROM topics WHERE id = $id"
    );
}

header("Location: index.php");
exit;
