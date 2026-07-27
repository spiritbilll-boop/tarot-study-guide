<?php
require_once(__DIR__ . "/../../common/config.php");
/*
 * admin/common/header.php
 *
 * Common header for all administration pages.
 */

if (!isset($page_title) || trim($page_title) == "")
{
    $page_title = "Tarot Study Guide Administration";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">

<title><?php echo htmlspecialchars($page_title); ?></title>

<style>

body
{
    font-family: Times, serif;
    background: #1a1a2e;
    color: white;
    text-align: center;
    margin: 0;
    padding: 20px;
}

h1
{
    color: #ffd966;
}

h2
{
    color: #ffb347;
    margin-top: 35px;
}

.admin-box
{
    display: inline-block;
    background: #162447;
    border: 2px solid #e43f5a;
    border-radius: 10px;
    padding: 30px;
    width: 95%;
    max-width: 1400px;
    box-sizing: border-box;
}

a
{
    color: #ffd966;
    text-decoration: none;
    font-weight: bold;
}

a:hover
{
    text-decoration: underline;
}

p
{
    margin: 12px;
}

.navbar
{
    margin-bottom: 25px;
    font-size: 18px;
}

.footer
{
    margin-top: 40px;
    font-size: 14px;
}
table
{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th
{
    background: #2d5c88;
    color: white;
    padding: 10px;
    border: 1px solid #555;
}

td
{
    padding: 8px 10px;
    border: 1px solid #555;
    text-align: left;
}

tr:nth-child(even)
{
    background: #22304a;
}

tr:hover
{
    background: #31496b;
}

.button
{
    display: inline-block;
    padding: 8px 14px;
    background: #2d5c88;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.button:hover
{
    background: #3b76aa;
    text-decoration: none;
}

.action-links a
{
    margin-right: 10px;
}
.action-links
{
    white-space: nowrap;
    text-align: center;
}
.action-links a
{
    display: inline-block;
    padding: 4px 10px;
    border: 1px solid #2d5c88;
    border-radius: 4px;
    background: #2d5c88;
    color: white;
    text-decoration: none;
}

.action-links a:hover
{
    background: #3b76aa;
    text-decoration: none;
}

input[type=text],
textarea,
select
{
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    margin-bottom: 12px;
}

input[type=submit],
button
{
    padding: 8px 16px;
}

</style>

</head>

<body>

<h1><?php echo htmlspecialchars($page_title); ?></h1>

<div class="navbar">

<a href="../index.php">Tarot Application</a> |
<a href="/tarot-study-guide/admin/">Administration Home</a> |
<a href="/tarot-study-guide/admin/study_notes/editor.php">Study Notes Editor</a> |
<a href="/tarot-study-guide/admin/entity_topics/">Entity Topics Manager</a>

</div>
