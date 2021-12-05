<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");
is_logged_in(true, "/Project/login.php");
if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "home.php"));
}
if (
    isset($_POST["name"]) && isset($_POST["description"]) &&
    isset($_POST["category"]) && isset($_POST["stock"]) &&
    isset($_POST["unit_price"]) && isset($_POST["visibility"])
) {
    $name = se($_POST, "name", "", false);
    $desc = se($_POST, "description", "", false);
    $category = se($_POST, "category", "", false);
    $stock = se($_POST, "stock", "", false);
    $uprice = se($_POST, "unit_price", "", false);
    $visibility = se($_POST, "visibility", "", false);
    $visibility === "Yes" ? $visibility = 1 : $visibility = 0;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Products (name, description, category, stock, unit_price, visibility) VALUES(:name, :desc, :category, :stock, :unit_price, :visibility)");
    try {
        $stmt->execute([":name" => $name, ":desc" => $desc, ":category" => $category, ":stock" => $stock, ":unit_price" => $uprice, ":visibility" => $visibility]);
        flash("Successfully added product: $name!", "success");
    } catch (PDOException $e) {

        if ($e->errorInfo[1] === 1062) {
            flash("A product with this name already exists, please add a different product", "warning");
        } else if ($e->errorInfo[1] === 1366) {
            flash("Stock takes an integer", "warning");
        } else {
            flash(var_export($e->errorInfo, true), "danger");
        }
    }
}
?>
<div class="container-fluid">
    <h1>Add Products</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control" id="name" name="name" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea rows=4 class="form-control" name="description" id="description" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="category">Category</label>
            <input class="form-control" id="category" name="category" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="stock">Stock</label>
            <input class="form-control" id="stock" name="stock" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="unit_price">Price</label>
            <input class="form-control" id="unit_price" name="unit_price" required />
        </div>
        <div class="mb-3">
            <label>Visible</label><br>
            <input type="radio" class="form-check-input" id="yes" name="visibility" value="Yes" checked required />
            <label class="form-check-label" for="yes">Yes</label>
            <input type="radio" class="form-check-input" id="no" name="visibility" value="No" required />
            <label class="form-label" for="no">No</label>
        </div>

        <input type="submit" class="btn btn-primary" value="Add Product" />
    </form>
</div>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>