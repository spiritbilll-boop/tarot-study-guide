<?php

require_once __DIR__ . "/common/db.php";
require_once __DIR__ . "/common/history.php";

$readings =
    get_recent_readings(
        $conn,
        1000
    );
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Reading History</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div id="header">
<h1>Reading History</h1>
</div>

<div id="content">

<?php
foreach ($readings as $reading)
{
?>

<div class="card">

<h2>
<?php
echo htmlspecialchars(
    $reading['created_at']
);
?>
</h2>

<p>

<strong>Question:</strong>

<?php
echo htmlspecialchars(
    $reading['user_question']
);
?>

</p>

<p>

<strong>Spread:</strong>

<?php
echo htmlspecialchars(
    $reading['reading_type']
);
?>

</p>

<p>

<strong>Cards:</strong><br>

<?php

$ids =
    explode(
        ',',
        $reading['card_ids']
    );

foreach ($ids as $index => $id)
{
    $id = (int) trim($id);

    if ($index > 0)
    {
        echo "<br>";
    }

echo
    "<strong>#" .
    $id .
    "</strong>  " .
    htmlspecialchars(
        get_card_name(
            $conn,
            $id
        )
    );
}

?>

</p>

</div>

<br>

<?php
}
?>

</div>

</body>

</html>
