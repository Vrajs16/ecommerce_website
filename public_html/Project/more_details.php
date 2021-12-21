<?php
if ($_SERVER['REQUEST_URI'] == "/Project/more_details.php") {
    header("Location: /Project/shop.php");
}
$commentsQuery = "SELECT Users.username, comment, rating From Ratings INNER JOIN Users on product_id = :id and user_id = Users.id";
$stmt = $db->prepare($commentsQuery); //dynamically generated query
try {
    $stmt->execute([":id" => se($item, "id", null, false)]);
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $comments = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}

?>
<style>
    fieldset,
    label {
        margin: 0;
        padding: 0;
    }

    /****** Style Star Rating Widget *****/

    .rating<?php se($item, "id"); ?> {
        border: none;
    }

    .rating<?php se($item, "id"); ?>>.input<?php se($item, "id"); ?> {
        display: none;
    }

    .rating<?php se($item, "id"); ?>>.label<?php se($item, "id"); ?>:before {
        margin: 2px;
        font-size: 1em;
        font-family: 'Font Awesome\ 5 Free';
        display: inline-block;
        content: "\f005";
    }

    .rating<?php se($item, "id"); ?>>.label<?php se($item, "id"); ?> {
        color: #ddd;
        float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .rating<?php se($item, "id"); ?>>.input<?php se($item, "id"); ?>:checked~.label<?php se($item, "id"); ?>,
    /* show gold star when clicked */
    .rating<?php se($item, "id"); ?>:not(:checked)>.label<?php se($item, "id"); ?>:hover,
    /* hover current star */
    .rating<?php se($item, "id"); ?>:not(:checked)>.label<?php se($item, "id"); ?>:hover~.label<?php se($item, "id"); ?> {
        color: #ffe234;
    }

    /* hover previous stars in list */

    .rating<?php se($item, "id"); ?>>.input<?php se($item, "id"); ?>:checked+.label<?php se($item, "id"); ?>:hover,
    /* hover current star when changing rating */
    .rating<?php se($item, "id"); ?>>.input<?php se($item, "id"); ?>:checked~.label<?php se($item, "id"); ?>:hover,
    .rating<?php se($item, "id"); ?>>.label<?php se($item, "id"); ?>:hover~.input<?php se($item, "id"); ?>:checked~.label<?php se($item, "id"); ?>,
    /* lighten current selection */
    .rating<?php se($item, "id"); ?>>.input<?php se($item, "id"); ?>:checked~.label<?php se($item, "id"); ?>:hover~.label<?php se($item, "id"); ?> {
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
                <p class="text-center h5">Details</p>
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
                <p class="text-center h5">Product Rating</p>
                <div class="justify-content-center text-center">
                    <span class="fas fa-star  fa-1x" style="color:#ddd;"></span>
                    <span class="fas fa-star" style="color:#ddd;"></span>
                    <span class="fas fa-star" style="color:#ddd;"></span>
                    <span class="fas fa-star" style="color:#ddd;"></span>
                    <span class="fas fa-star" style="color:#ddd;"></span><br>
                    <span class="ml-2">4.5</span>
                </div>
                <hr>
                <p class="text-center h5">Your Rating</p>
                <form action="/Project/shop.php" method="POST">
                    <div style="width:110px; margin:auto;">
                        <fieldset class="rating<?php se($item, "id"); ?>">
                            <input class="input<?php se($item, "id"); ?>" type="radio" id="star5" name="rating" value="5" /><label class="label<?php se($item, "id"); ?> fas fa-star" for="star5"></label>
                            <input class="input<?php se($item, "id"); ?>" type="radio" id="star4" name="rating" value="4" /><label class="label<?php se($item, "id"); ?> fas fa-star" for="star4"></label>
                            <input class="input<?php se($item, "id"); ?>" type="radio" id="star3" name="rating" value="3" /><label class="label<?php se($item, "id"); ?> fas fa-star" for="star3"></label>
                            <input class="input<?php se($item, "id"); ?>" type="radio" id="star2" name="rating" value="2" /><label class="label<?php se($item, "id"); ?> fas fa-star" for="star2"></label>
                            <input class="input<?php se($item, "id"); ?>" type="radio" id="star1" name="rating" value="1" /><label class="label<?php se($item, "id"); ?> fas fa-star" for="star1"></label>
                        </fieldset>
                    </div>
                </form>
                <hr>
                <p class="text-center h5">Comments</p>
                <?php
                if (isset($comments) && se($item, "stock", null, false) > 0) { ?>
                    <?php foreach ($comments as $x) : ?>
                        <div style="border:2px solid black; padding:5px;margin:5px;">
                            <p><?php echo ucfirst(se($x, "username", null, false)) ?>:<br><?php se($x, "comment") ?></p>
                        </div>
                        <br>
                    <?php endforeach ?>
                <?php } else { ?>
                    <div style="border:2px solid black; padding:5px;margin:5px;">
                        <p>No comments</p>
                    </div>
                    <br>
                <?php } ?>

                <form method="POST">
                    <div class="form-group row text-center">
                        <div class="col-xs-2">
                            <input type="hidden" name="id" value="<?php se($item, "id") ?>">
                            <input type="text" class="form-control mb-2" id="message-text" name="comment-text" placeholder="Add a comment..."></input>
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