<!-- Get the id of the current product that you want to edit. Based on that id query the db and populate the edit fields make it easier to change the product once you have selected it. !-->
<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");
is_logged_in(true, "/Project/login.php");
if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}
$results = array(0 => array("name" => "", "description" => "", "stock" => "", "unit_price" => "", "category" => "", "visibility" => "",));
if (isset($_POST["update"])) {
    $id = se($_POST, "id", "", false);
    $id = 4;
    $name = se($_POST, "name", "", false);
    $desc = se($_POST, "description", "", false);
    $category = se($_POST, "category", "", false);
    $stock = se($_POST, "stock", "", false);
    $uprice = se($_POST, "unit_price", "", false);
    $visibility = se($_POST, "visibility", "", false);
    $visibility === "Yes" ? $visibility = 1 : $visibility = 0;
    $db = getDB();
    $stmt = $db->prepare("UPDATE Products set description = :desc, category = :category, stock = :stock, unit_price = :unit_price, visibility = :visibility WHERE id = :id");
    try {
        $stmt->execute([":desc" => $desc, ":category" => $category, ":stock" => $stock, ":unit_price" => $uprice, ":visibility" => $visibility, ":id" => $id]);
        flash("Successfully updated product: $name!", "success");
    } catch (PDOException $e) {

        // if ($e->errorInfo[1] === 1062) {
        //     flash("Erroring updating, Maybe this product already exists", "warning");
        // } else if ($e->errorInfo[1] === 1366) {
        //     flash("Stock takes an integer", "warning");
        // } else {
        flash(var_export($e->errorInfo, true), "danger");
        // }
    }
}

if (isset($_POST["edit"]) && isset($_POST["id"])) {

    $db = getDB();
    //Sort and Filters

    //split query into data and total
    $base_query = "SELECT id, name, description, stock, unit_price, category, visibility FROM Products WHERE id = ";
    $query = $_POST['id'];
    $stmt = $db->prepare($base_query . $query); //dynamically generated query
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $results = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
?>

<div class="container-fluid">
    <?php foreach ($results as $item) : ?>
        <h1>Update Products</h1>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php se($item, "name"); ?>" readonly />
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea rows=4 class="form-control" name="description" id="description" required><?php se($item, "description"); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category">Category</label>
                <input class="form-control" id="category" name="category" value="<?php se($item, "category"); ?>" required />
            </div>
            <div class=" mb-3">
                <label class="form-label" for="stock">Stock</label>
                <input class="form-control" id="stock" name="stock" value="<?php se($item, "stock"); ?>" required />
            </div>
            <div class=" mb-3">
                <label class="form-label" for="unit_price">Price</label>
                <input class="form-control" id="unit_price" name="unit_price" value="<?php se($item, "unit_price"); ?>" required />
            </div>
            <div class="mb-3">
                <label>Visible</label><br>
                <?php if (se($item, "visibility", "", false) === "1") : ?>
                    <input type="radio" class="form-check-input" id="yes" name="visibility" value="Yes" checked />
                    <label class="form-check-label" for="yes">Yes</label>
                    <input type="radio" class="form-check-input" id="no" name="visibility" value="No" />
                    <label class="form-label" for="no">No</label>
                <?php elseif (se($item, "visibility", "", false) === "0") : ?>
                    <input type="radio" class="form-check-input" id="yes" name="visibility" value="Yes" />
                    <label class="form-check-label" for="yes">Yes</label>
                    <input type="radio" class="form-check-input" id="no" name="visibility" value="No" checked />
                    <label class="form-label" for="no">No</label>
                <?php else : ?>
                    <input type="radio" class="form-check-input" id="yes" name="visibility" value="Yes" checked />
                    <label class="form-check-label" for="yes">Yes</label>
                    <input type="radio" class="form-check-input" id="no" name="visibility" value="No" />
                    <label class="form-label" for="no">No</label>
                <?php endif ?>

            </div>
            <input type="hidden" value="<?php se($item, "id"); ?>" />
            <input type="submit" class="btn btn-primary" value="Update Product" name="update" />
        </form>
    <?php endforeach ?>
</div>

<div class="mt-5"></div>
<?php require(__DIR__ . "/../filter.php"); ?>
<div class="mt-5"></div>
<?php require(__DIR__ . "/../show_products.php"); ?>
<?php
require_once(__DIR__ . "/../../../partials/flash.php");
?>