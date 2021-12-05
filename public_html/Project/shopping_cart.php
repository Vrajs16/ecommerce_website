<?php

if (isset($_POST["add"])) {
    if (!is_logged_in()) {
        flash("Log in to add to cart!", "warning");
    }
}
require(__DIR__ . "/../../partials/flash.php");
?>
<?php if (is_logged_in()) : ?>
    <input type="image" width="50px" class="float-end p-1" src="./Images/shopping-cart.png" data-bs-toggle="modal" data-bs-target="#ShoppingCart">
    <div class="modal fade" id="ShoppingCart" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">ShoppingCart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <p class="float-start">Subtotal: </p><br>
                    <button type="button" class="btn btn-secondary float-start" data-bs-dismiss="modal">Update Cart</button>
                    <button type="button" class="btn btn-primary float-end" data-bs-dismiss="modal">Checkout</button>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>