<?php

/*
============================================================

database.php

Shared database connection

============================================================
*/

$conn = new mysqli(
    "localhost",
    "tarot_db",
    "tarot_db",
    "tarot_db"
);

if ($conn->connect_error)
{
    die("Database connection failed: " .
        $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
