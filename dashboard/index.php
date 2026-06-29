<?php
require_once "common.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link
rel="stylesheet"
href="../css/theme.css">
<link
rel="stylesheet"
href="../css/style.css">
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
<div class="panel">
<h2>
Administration
</h2>
<ul class="menu">
<li class="menu-item">
<a href="../study_notes/">
Study Notes
</a>
</li>
<li class="menu-item">
<a href="../cards/">
Card Browser
</a>
</li>
<li class="menu-item">
Search (Coming Soon)
</li>
<li class="menu-item">
Reports (Coming Soon)
</li>
</ul>
</div>
<hr>
<div class="panel">
<h2>
Statistics
</h2>
<p>
Cards:
<strong>
<?php
echo
get_card_count(
    $conn
);
?>
</strong>
</p>
<p>
Study Notes:
<strong>
<?php
echo
get_study_note_count(
    $conn
);
?>
</strong>
</p>
<p>
Study Notes Enabled:
<strong>
<?php
echo
get_enabled_study_note_count(
    $conn
);
?>
</strong>
</p>

<p>

Study Notes Disabled:

<strong>

<?php

echo
get_disabled_study_note_count(
    $conn
);

?>

</strong>

</p>

<p>

Cards With Study Notes:

<strong>

<?php
echo
get_cards_with_study_notes(
    $conn
);

?>

</strong>

</p>
<p>

Cards Without Study Notes:

<strong>

<?php

echo
get_cards_without_study_notes(
    $conn
);

?>

</strong>

</p>
<p>

Coverage:

<strong>

<?php

echo
number_format(
    get_coverage_percentage(
        $conn
    ),
    2
)
. "%";

?>

</strong>

</p>

</div>
<hr>
<div class="panel">
<h2>
System
</h2>
<p>
Database:
Connected
</p>
</div>
</body>
</html>
