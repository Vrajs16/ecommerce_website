<?php
$results = [];
$db = getDB();
//Sort and Filters
$col = se($_GET, "col", "unit_price", false);
//allowed list
if (!in_array($col, ["unit_price", "stock", "name", "created", "category"])) {
    $col = "unit_price"; //default value, prevent sql injection
}
$order = se($_GET, "order", "asc", false);
//allowed list
if (!in_array($order, ["asc", "desc"])) {
    $order = "asc"; //default value, prevent sql injection
}
$name = se($_GET, "name", "", false);

//split query into data and total
$base_query = "SELECT id, name, description, stock, unit_price, category, visibility FROM Products WHERE stock > 0 and visibility = 1";
$query = "";
$params = []; //define default params, add keys as needed and pass to execute
//apply name filter
if (!empty($name)) {
    $query .= " AND name like :name";
    $params[":name"] = "%$name%";
}
//apply column and order sort
if (!empty($col) && !empty($order)) {
    $query .= " ORDER BY $col $order"; //be sure you trust these values, I validate via the in_array checks above
}

//get the records
$query .= " limit 10";
$stmt = $db->prepare($base_query . $query); //dynamically generated query
foreach ($params as $key => $value) {
    $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $type);
}

$params = null; //set it to null to avoid issues
try {
    $stmt->execute($params);
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $results = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}
?>
<div class="message2-info">FILTER</div>
<form class="row row-cols-auto g-3 align-items-center justify-content-center">
    <div class="col">
        <div class="input-group">
            <div class="input-group-text">Search</div>
            <input class="form-control" name="name" value="<?php se($name); ?>" />
        </div>
    </div>
    <div class="col col-md-6">
        <div class="input-group">
            <div class="input-group-text">Sort By</div>
            <!-- make sure these match the in_array filter above-->
            <select class="form-control" name="col" value="<?php se($col); ?>">
                <option value="unit_price">Price</option>
                <option value="stock">Stock</option>
                <option value="name">Name</option>
                <option value="created">Created</option>
                <option value="category">Category</option>
            </select>
            <script>
                //quick fix to ensure proper value is selected since
                //value setting only works after the options are defined and php has the value set prior
                document.forms[0].col.value = "<?php se($col); ?>";
            </script>
            <select class="form-control" name="order" value="<?php se($order); ?>">
                <option value="asc">Low-High</option>
                <option value="desc">High-Low</option>
            </select>
            <script>
                //quick fix to ensure proper value is selected since
                //value setting only works after the options are defined and php has the value set prior
                document.forms[0].order.value = "<?php se($order); ?>";
            </script>
        </div>
    </div>
    <div class="col">
        <div class="input-group">
            <input type="submit" class="btn btn-primary" value="Apply" />
        </div>
    </div>
</form>
</div>