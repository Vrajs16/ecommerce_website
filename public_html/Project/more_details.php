<?php
if ($_SERVER['REQUEST_URI'] == "/Project/more_details.php") {
    header("Location: /Project/shop.php");
}
$commentsQuery = "SELECT Users.username, comment, product_id From Ratings INNER JOIN Users on Users.id =  Ratings.user_id where Ratings.product_id = :id";
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
$uid = get_user_id();
$ratingsQueryPerUser = "SELECT rating, product_id, user_id From Ratings INNER JOIN Users on Users.id = Ratings.user_id where Ratings.product_id = :id";
$stmt = $db->prepare($ratingsQueryPerUser); //dynamically generated query
try {
    $stmt->execute([":id" => se($item, "id", null, false)]);
    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($r) {
        $ratingsForUser = $r;
    }
} catch (PDOException $e) {
    flash("<pre>" . var_export($e, true) . "</pre>");
}

?>
<style>
    .ratingLabel<?php se($item, "id") ?> {
        margin: 0;
        padding: 0;
    }

    /****** Style Star Rating Widget *****/


    .ratingInput<?php se($item, "id") ?> {
        display: none;
    }

    .ratingLabel<?php se($item, "id") ?>:before {
        margin: 2px;
        font-size: 1em;
        font-family: 'Font Awesome\ 5 Free';
        display: inline-block;
        content: "\f005";
    }

    .ratingLabel<?php se($item, "id") ?> {
        color: #ddd;
        float: right;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/
    /* show gold star when clicked */
    .ratingLabel<?php se($item, "id") ?>:hover,
    /* hover current star */
    .ratingLabel<?php se($item, "id") ?>:hover~.ratingLabel<?php se($item, "id") ?> {
        color: #ffe234;
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
                <form name="form<?php se($item, "id") ?>" method="POST">
                    <div style="width:110px; margin:auto;">
                        <input type="hidden" name="id" value=<?php se($item, "id"); ?>>
                        <input class="ratingInput<?php se($item, "id") ?>" onclick="document.form<?php se($item, 'id') ?>.submit()" type="radio" id="star5<?php se($item, "id") ?>" name="rating" value="5" /><label class="ratingLabel<?php se($item, "id") ?> fas fa-star" for="star5<?php se($item, "id") ?>"></label>
                        <input class="ratingInput<?php se($item, "id") ?>" onclick="document.form<?php se($item, 'id') ?>.submit()" type="radio" id="star4<?php se($item, "id") ?>" name="rating" value="4" /><label class="ratingLabel<?php se($item, "id") ?> fas fa-star" for="star4<?php se($item, "id") ?>"></label>
                        <input class="ratingInput<?php se($item, "id") ?>" onclick="document.form<?php se($item, 'id') ?>.submit()" type="radio" id="star3<?php se($item, "id") ?>" name="rating" value="3" /><label class="ratingLabel<?php se($item, "id") ?> fas fa-star" for="star3<?php se($item, "id") ?>"></label>
                        <input class="ratingInput<?php se($item, "id") ?>" onclick="document.form<?php se($item, 'id') ?>.submit()" type="radio" id="star2<?php se($item, "id") ?>" name="rating" value="2" /><label class="ratingLabel<?php se($item, "id") ?> fas fa-star" for="star2<?php se($item, "id") ?>"></label>
                        <input class="ratingInput<?php se($item, "id") ?>" onclick="document.form<?php se($item, 'id') ?>.submit()" type="radio" id="star1<?php se($item, "id") ?>" name="rating" value="1" /><label class="ratingLabel<?php se($item, "id") ?> fas fa-star" for="star1<?php se($item, "id") ?>"></label>
                    </div>
                    <?php
                    if (isset($ratingsForUser)) {
                        foreach ($ratingsForUser as $currentRatingForthis) {
                            if ($currentRatingForthis['rating'] > 0 && $currentRatingForthis['user_id'] == get_user_id() && $currentRatingForthis['product_id'] == se($item, "id", null, false) && is_logged_in()) { ?>
                                <br>
                                <p class="text-center">Your Rating: <?php se($currentRatingForthis, "rating"); ?></p>
                    <?php }
                        }
                    } ?>
                </form><br>
                <hr>
                <p class="text-center h5">Comments</p>
                <?php
                if (isset($comments)) {
                    $currentCOUNT = 0;
                    foreach ($comments as $comment) {
                        if ($comment['comment'] != "" && $comment['product_id'] == se($item, "id", null, false) && is_logged_in() && se($item, "stock", null, false) > 0) { ?>
                            <div style="border:2px solid black; padding:5px;margin:5px;">
                                <p><?php echo ucfirst(se($comment, "username", null, false)) ?>:<br><?php se($comment, "comment") ?></p>
                            </div>
                            <br>
                        <?php $currentCOUNT += 1;
                        }
                    }
                    if ($currentCOUNT == 0) { ?>
                        <div style="border:2px solid black; padding:5px;margin:5px;">
                            <p class="text-center">No comments</p>
                        </div>
                        <br>
                    <?php }
                } else { ?>
                    <div style="border:2px solid black; padding:5px;margin:5px;">
                        <p class="text-center">No comments</p>
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