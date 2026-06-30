<?php

/*
============================================================

Tarot Study Guide

Shared Database Connection

This file establishes the application's single MySQLi
database connection.

Every PHP page requiring database access should include:

    require_once 'common/db.php';

============================================================
*/

$host = 'localhost';
$db   = 'tarot_db';
$user = 'tarot_db';
$pass = 'tarot_db';

$conn =
    new mysqli(
        $host,
        $user,
        $pass,
        $db
    );

if (
    $conn->connect_errno
)
{
    die(
        "Database connection failed: "
        .
        $conn->connect_error
    );
}

$conn->set_charset(
    "utf8mb4"
);
