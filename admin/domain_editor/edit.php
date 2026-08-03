<?php

require_once(__DIR__ . "/../../common/db.php");

include(__DIR__ . "/../common/header.php");

$id =
    isset($_GET["id"])
        ? intval($_GET["id"])
        : 0;

$domain =
[
    "id"            => 0,
    "name"          => "",
    "description"   => "",
    "display_order" => 100,
    "enabled"       => 1
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $domain["name"] =
        trim($_POST["name"]);

    $domain["description"] =
        trim($_POST["description"]);

    $domain["display_order"] =
        intval($_POST["display_order"]);

    $domain["enabled"] =
        isset($_POST["enabled"])
            ? 1
            : 0;

    if ($id == 0)
    {
        $stmt = mysqli_prepare(
            $conn,
            "
            INSERT INTO domains
            (
                name,
                description,
                display_order,
                enabled
            )
            VALUES
            (
                ?, ?, ?, ?
            )
            "
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssii",
            $domain["name"],
            $domain["description"],
            $domain["display_order"],
            $domain["enabled"]
        );
    }
    else
    {
        $stmt = mysqli_prepare(
            $conn,
            "
            UPDATE domains
            SET
                name = ?,
                description = ?,
                display_order = ?,
                enabled = ?
            WHERE
                id = ?
            "
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssiii",
            $domain["name"],
            $domain["description"],
            $domain["display_order"],
            $domain["enabled"],
            $id
        );
    }

    if (!$stmt)
    {
        die(mysqli_error($conn));
    }

    try
    {
        mysqli_stmt_execute($stmt);

        if ($id == 0)
        {
            $id = mysqli_insert_id($conn);
        }

        mysqli_stmt_close($stmt);

        header(
            "Location: edit.php?id="
            .
            $id
            .
            "&saved=1"
        );

        exit;
    }
    catch (mysqli_sql_exception $e)
    {
        if ($e->getCode() == 1062)
        {
            $error =
                "A domain with that name already exists.";
        }
        else
        {
            $error =
                $e->getMessage();
        }
    }
}

if ($id > 0)
{
    $stmt = mysqli_prepare(
        $conn,
        "
        SELECT
            *
        FROM
            domains
        WHERE
            id = ?
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    $result =
        mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result))
    {
        $domain = $row;
    }

    mysqli_stmt_close($stmt);
}

?>

<div class="admin-box">

<?php if ($error != "") : ?>

<div
style="
background:#8b1e1e;
color:white;
padding:12px;
margin-bottom:20px;
border-radius:6px;
">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<?php if (isset($_GET["saved"])) : ?>

<div
style="
background:#2e7d32;
color:white;
padding:12px;
margin-bottom:20px;
border-radius:6px;
">

&#10003; Domain saved successfully.

</div>

<?php endif; ?>

<h2>

<?= ($id > 0)
        ? "Edit Domain"
        : "Add Domain" ?>

</h2>

<form method="post">

<p>

<label>

Domain Name

</label>

<br>

<input
type="text"
name="name"
value="<?= htmlspecialchars($domain["name"]) ?>"
required>

</p>

<p>

<label>

Description

</label>

<br>

<textarea
name="description"
rows="6"><?= htmlspecialchars($domain["description"]) ?></textarea>

</p>

<p>

<label>

Display Order

</label>

<br>

<input
type="text"
name="display_order"
value="<?= htmlspecialchars($domain["display_order"]) ?>">

</p>

<p>

<label>

<input
type="checkbox"
name="enabled"
<?= $domain["enabled"] ? "checked" : "" ?>>

Enabled

</label>

</p>

<p>

<input
type="submit"
value="Save Domain">

&nbsp;

<a
class="button"
href="index.php">

Cancel

</a>

</p>

</form>

</div>

<?php

include(__DIR__ . "/../common/footer.php");

?>
