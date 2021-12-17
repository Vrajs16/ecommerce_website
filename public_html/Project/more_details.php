<?php
if ($_SERVER['REQUEST_URI'] == "/Project/more_details.php") {
    header("Location: /Project/shop.php");
}
?>
<style>
    fieldset,
    label {
        margin: 0;
        padding: 0;
    }

    /****** Style Star Rating Widget *****/

    .rating {
        border: none;
    }

    .rating>input {
        display: none;
    }

    .rating>label:before {
        margin: 2px;
        font-size: 1em;
        font-family: 'Font Awesome\ 5 Free';
        display: inline-block;
        content: "\f005";
    }

    .rating>label {
        color: #ddd;
        float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating>input:checked~label,
    /* show gold star when clicked */
    .rating:not(:checked)>label:hover,
    /* hover current star */
    .rating:not(:checked)>label:hover~label {
        color: #ffe234;
    }

    /* hover previous stars in list */

    .rating>input:checked+label:hover,
    /* hover current star when changing rating */
    .rating>input:checked~label:hover,
    .rating>label:hover~input:checked~label,
    /* lighten current selection */
    .rating>input:checked~label:hover~label {
        color: #ffb000;
    }
</style>
<script src="https://kit.fontawesome.com/6d0e983cff.js" crossorigin="anonymous"></script>
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
                <p class="text-center h5">Product Rating</p>
                <div class="justify-content-center text-center">
                    <span class="fas fa-star  fa-1x" style="color:#ffe234;"></span>
                    <span class="fas fa-star" style="color:#ffe234;"></span>
                    <span class="fas fa-star" style="color:#ffe234;"></span>
                    <span class="fas fa-star" style="color:#ffe234;"></span>
                    <span class="fas fa-star-half-alt" style="color:#ffe234;"></span>
                </div>
                <hr>
                <p class="text-center h5">Your Rating</p>
                <form action="/Project/shop.php" method="POST">
                    <div style="width:110px; margin:auto;">
                        <fieldset class="rating">
                            <input type="radio" id="star5" name="rating" value="5" /><label class="fas fa-star" for="star5"></label>
                            <input type="radio" id="star4" name="rating" value="4" /><label class="fas fa-star" for="star4"></label>
                            <input type="radio" id="star3" name="rating" value="3" /><label class="fas fa-star" for="star3"></label>
                            <input type="radio" id="star2" name="rating" value="2" /><label class="fas fa-star" for="star2"></label>
                            <input type="radio" id="star1" name="rating" value="1" /><label class="fas fa-star" for="star1"></label>
                        </fieldset>
                    </div>
                </form>
                <hr>
                <p class="text-center h5">Details</p><br>
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
                <hr>
                <p class="text-center h5">Comments</p><br>
                <hr>
                <form action="/Project/shop.php" method="POST">
                    <div class="form-group row text-center">
                        <div class="col-xs-2">
                            <label for="message-text" class="col-form-label">Add a comment</label>
                            <input type="text" class="form-control" id="message-text" name="comment-text"></input>
                            <input type="submit" class="btn btn-primary" name="comment" value="Comment" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>