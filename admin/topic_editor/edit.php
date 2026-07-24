<?php
require_once(__DIR__ . "/../../common/db.php");
include(__DIR__ . "/../common/header.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$topic = [
    "id" => 0,
    "domain_id" => "",
    "name" => "",
    "description" => "",
    "enabled" => 1
];

$domains = [];
$error = "";

$result = mysqli_query(
    $conn,
    "SELECT id, name
     FROM domains
     WHERE enabled = 1
     ORDER BY display_order, name"
);

while ($row = mysqli_fetch_assoc($result))
{
    $domains[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $topic["domain_id"] = intval($_POST["domain_id"]);
    $topic["name"] = trim($_POST["name"]);
    $topic["description"] = trim($_POST["description"]);

    if ($id == 0)
    {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO topics
             (domain_id, name, description)
             VALUES (?, ?, ?)"
        );

        if (!$stmt)
        {
            die(mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $topic["domain_id"],
            $topic["name"],
            $topic["description"]
        );

try
{
    mysqli_stmt_execute($stmt);

    $id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Post/Redirect/Get
    header("Location: edit.php?id=" . $id . "&saved=1");
    exit;
}
catch (mysqli_sql_exception $e)
{
    switch ($e->getCode())
    {
        case 1062:
            $error = 'A topic with that name already exists in the selected domain.';
            break;

        default:
            $error = $e->getMessage();
            break;
    }
}

mysqli_stmt_close($stmt);
    }
}

if ($id > 0)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, domain_id, name, description, enabled
         FROM topics
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result))
    {
        $topic = $row;
    }

    mysqli_stmt_close($stmt);
}
?>

<div class="admin-box">

<?php if ($error != ""): ?>
<div style="background:#8b1e1e;color:white;padding:12px;margin-bottom:20px;border-radius:6px;">
<?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<?php if (isset($_GET["saved"])): ?>
<div style="background:#2e7d32;color:white;padding:12px;margin-bottom:20px;border-radius:6px;">
✓ Topic saved successfully.
</div>
<?php endif; ?>

<h2><?php echo ($id > 0) ? "Edit Topic" : "Add Topic"; ?></h2>

<form method="post" action="">

<p>
<label for="domain_id">Domain</label><br>
<select id="domain_id" name="domain_id">
<?php foreach ($domains as $domain): ?>
<option value="<?php echo $domain["id"]; ?>" <?php if ($domain["id"] == $topic["domain_id"]) echo "selected"; ?>>
<?php echo htmlspecialchars($domain["name"]); ?>
</option>
<?php endforeach; ?>
</select>
</p>

<p>
<label for="name">Topic Name</label><br>
<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($topic["name"]); ?>">
</p>

<p>
<label for="description">Description</label><br>
<textarea id="description" name="description" rows="6"><?php echo htmlspecialchars($topic["description"]); ?></textarea>
</p>

<p><input type="submit" value="Save Topic"></p>

</form>

</div>

<?php
include(__DIR__ . "/../common/footer.php");
?>
