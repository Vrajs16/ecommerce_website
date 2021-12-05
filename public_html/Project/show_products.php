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
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#modal<?php se($item, "id"); ?>">
                            More Details
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modal<?php se($item, "id"); ?>" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="ModalLabel"><?php se($item, "name"); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if (has_role("Admin")) : ?>
                                            <form method="POST" action="/Project/admin/edit_products.php">
                                                <input type="hidden" name="id" value="<?php se($item, "id") ?>">
                                                <input type="submit" class="btn btn-light float-end" value="Edit" name="edit">
                                            </form>
                                        <?php endif ?>
                                        <p> Id: <?php se($item, "id"); ?> </p>
                                        <p> Price: <?php se($item, "unit_price"); ?></p>
                                        <p> Description: <?php se($item, "description"); ?></p>
                                        <p> Stock: <?php se($item, "stock"); ?></p>
                                        <p> Category: <?php se($item, "category"); ?></p>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        Cost: $<?php se($item, "unit_price"); ?>
                        <?php if (basename($_SERVER['PHP_SELF']) !== "list_products.php" && basename($_SERVER['PHP_SELF']) !== "edit_products.php") : ?>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?php se($item, "id") ?>">
                                <input type="submit" class="btn btn-primary float-end" value="Add To Cart" name="add">
                            </form>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>