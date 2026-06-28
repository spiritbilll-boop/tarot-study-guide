<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>
Tarot Study Guide CMS
</title>
</head>
<body>
<h1>
Tarot Study Guide CMS
</h1>
<p>
Version:
<?php
echo
trim(
    file_get_contents(
        "../VERSION"
    )
);
?>
</p>
<hr>
<h2>
Administration
</h2>
<ul>
<li>
<a href="../study_notes/">
Study Notes
</a>
</li>
<li>
<a href="../cards/">
Card Browser
</a>
</li>
<li>
Search
(Coming Soon)
</li>
<li>
Reports
(Coming Soon)
</li>
</ul>
<hr>
<h2>
Statistics
</h2>
<p>
Coming Soon
</p>
<hr>
<h2>
System
</h2>
<p>
Database:
Connected
</p>
</body>
</html>
