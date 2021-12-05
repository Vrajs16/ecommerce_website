<div class="container-fluid">
    <div class="row row-cols-1 row-cols-md-5 g-4 justify-content-center ">
        <?php foreach ($results as $item) : ?>
            <?php if (se($item, "visibility", "", false) === "0" && !has_role("Admin")) : ?>
                <?php continue; ?>
            <?php endif ?>
            <div class="col">
                <div class="card h-100 bg-light">
                    <div class="card-header fs-4">
                        <?php if (has_role("Admin")) : ?>
                            <form method="POST" action="/Project/admin/edit_products.php">
                                <input type="hidden" name="id" value="<?php se($item, "id") ?>">
                                <input type="submit" class="btn btn-light float-end" value="Edit" name="edit" />
                            </form>
                        <?php endif ?>
                        <?php se($item, "name"); ?>
                    </div>
                    <div class="card-body h-100">
                        <p class="card-text ">Description: <br><?php se($item, "description"); ?></p>
                        <?php require(__DIR__ . "/more_details.php") ?>
                    </div>
                    <div class="card-footer">
                        Cost: $<?php se($item, "unit_price"); ?>
                        <?php if (basename($_SERVER['PHP_SELF']) !== "list_products.php" && basename($_SERVER['PHP_SELF']) !== "edit_products.php") : ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php se($item, "id") ?>">
                                <input type="submit" class="btn btn-primary float-end" value="Add To Cart" name="add_to_cart">
                            </form>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>