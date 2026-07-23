<?php
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
    min-width: 500px;
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

</style>

</head>

<body>

<h1><?php echo htmlspecialchars($page_title); ?></h1>

<div class="navbar">

<a href="../index.php">Tarot Application</a> |
<a href="../knowledge_graph/">Knowledge Graph</a> |
<a href="../study_notes/editor.php">Study Notes</a> |
<a href="../entity_topics/">Entity Topics</a>

</div>

<div class="admin-box">
