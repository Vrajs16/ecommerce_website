<?php
$results = [];

$db = getDB();
$stmt = $db->prepare("SELECT id, name, description, stock, unit_price, category, visibility FROM Products WHERE stock > 0 LIMIT 50");

try {
    $stmt->execute();
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $results = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}

?>
<div class="container-fluid">
    <div class="row row-cols-1 row-cols-md-5 g-4 justify-content-center ">
        <?php foreach ($results as $item) : ?>
            <?php if (se($item, "visibility", "", false) === "0") : ?>
                <?php continue; ?>
            <?php else : ?>
                <div class="col">
                    <div class="card h-100 bg-light">
                        <div class="card-header">
                            Product: <br> <?php se($item, "name"); ?>
                        </div>

                        <div class="card-body">

                            <p class="card-text">Description: <br><?php se($item, "description"); ?></p>
                        </div>
                        <div class="card-footer">
                            Cost: $<?php se($item, "unit_price"); ?>
                            <button onclick="purchase('<?php se($item, 'id'); ?>')" class="btn btn-primary">Add To Cart</button>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
</div>