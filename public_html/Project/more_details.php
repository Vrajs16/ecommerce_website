<?php
if ($_SERVER['REQUEST_URI'] == "/Project/more_details.php") {
    header("Location: /Project/shop.php");
}
?>
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
            <div class="modal-footer justify-content-center h5">
                <p>Ratings</p>
            </div>
            <hr>
            <div class="modal-body justify-content-center text-center align-items-center">
                <p>Comments here</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>