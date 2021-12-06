<?php
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
if (isset($_POST["add_to_cart"])) {
    if (!is_logged_in()) {
        flash("Log in to add to cart!", "warning");
    } else {
        $prodid = se($_POST["id"], null, "", false); //Product id
        $userid = se(get_user_id(), null, "", false); //User id
        $db = getDB();

        $query1 = "SELECT name, unit_price FROM Products where id=:prodid";
        $stmt = $db->prepare($query1); //dynamically generated query
        try {
            $stmt->execute([":prodid" => $prodid]);
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                $results1 = $r;
            }
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }

        $query6 = "SELECT desired_quantity FROM Cart where user_id=:userid and product_id=:prodid";
        $stmt = $db->prepare($query6);
        try {
            $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                $quantity = $r;
            }
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
        if (isset($quantity)) {
            $name = se($results1[0], "name", "", false);
            $query2 = "UPDATE Cart set desired_quantity = desired_quantity + 1 Where user_id=:userid and product_id=:prodid";
            $stmt = $db->prepare($query2); //dynamically generated query
            $params = [":userid" => $userid, ":prodid" => $prodid];
            foreach ($params as $key => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $type);
            }
            $params = null;
            try {
                $stmt->execute($params);
                flash("Successfully added product: $name to shopping cart!", "success");
            } catch (PDOException $e) {
                flash("<pre>" . var_export($e, true) . "</pre>");
            }
        } else {
            $cost = se($results1[0], "unit_price", "", false);
            $name = se($results1[0], "name", "", false);
            $query2 = "INSERT into Cart (product_id, user_id, desired_quantity, unit_cost) VALUES (:prodid, :userid, 1, :cost) ";

            $stmt = $db->prepare($query2); //dynamically generated query
            $params = [":prodid" => $prodid, ":userid" => $userid, ":cost" => $cost];
            foreach ($params as $key => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $type);
            }
            $params = null;
            try {
                $stmt->execute($params);
                flash("Successfully added product: $name to shopping cart!", "success");
            } catch (PDOException $e) {
                flash("<pre>" . var_export($e, true) . "</pre>");
            }
        }
    }
}
if (isset($_POST["empty_cart"])) {
    $db = getDB();
    $userid = se(get_user_id(), null, "", false); //User id
    $query5 = "DELETE From Cart where user_id=:userid";
    $stmt = $db->prepare($query5); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid]);
        flash("CLEARED CART!", "success");
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
if (isset($_POST["remove_product"])) {
    $db = getDB();
    $prodid = se($_POST["prodid"], null, "", false); //Product id
    $userid = se(get_user_id(), null, "", false); //User id
    $query5 = "DELETE From Cart where user_id=:userid and product_id=:prodid";
    $stmt = $db->prepare($query5); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
        $name = $_POST['name'];
        flash("CLEARED ITEM: $name!", "success");
        echo '<script type="text/javascript">$(window).on("load", function() {$("#ShoppingCart").modal("show");});</script>';
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
if (isset($_POST["plus"])) {
    $prodid = se($_POST["prodid"], null, "", false); //Product id
    $userid = se(get_user_id(), null, "", false); //User id
    $db = getDB();
    $inputQuant = se($_POST, "inputQuant", "", false);
    $query6 = "SELECT desired_quantity FROM Cart where user_id=:userid and product_id=:prodid";
    $stmt = $db->prepare($query6);
    try {
        $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $quantity = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $outputQuant = se($quantity[0], "desired_quantity", "", false);
    if ($inputQuant == $outputQuant) {
        $query5 = "UPDATE Cart set desired_quantity = desired_quantity + 1 Where user_id=:userid and product_id=:prodid";
    } elseif (ctype_digit($inputQuant) && $inputQuant >= 0) {
        $inputQuant = intval($inputQuant);
        $query5 = "UPDATE Cart set desired_quantity = $inputQuant Where user_id=:userid and product_id=:prodid";
    } elseif (ctype_digit($inputQuant) && $inputQuant < 0) {
        $query5 = "UPDATE Cart set desired_quantity = 0 Where user_id=:userid and product_id=:prodid";
    } else {
        $query5 = "UPDATE Cart set desired_quantity = $outputQuant Where user_id=:userid and product_id=:prodid";
    }

    $stmt = $db->prepare($query5); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
        $name = $_POST['name'];
        flash("Added Quantity 1 for ITEM: $name!", "success");
        echo '<script type="text/javascript">$(window).on("load", function() {$("#ShoppingCart").modal("show");});</script>';
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $query7 = "DELETE FROM Cart WHERE (user_id=:userid) and (desired_quantity <= 0)";
    $stmt = $db->prepare($query7); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid],);
        echo '<script type="text/javascript">$(window).on("load", function() {$("#ShoppingCart").modal("show");});</script>';
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
if (isset($_POST["minus"])) {
    $prodid = se($_POST["prodid"], null, "", false); //Product id
    $userid = se(get_user_id(), null, "", false); //User id
    $db = getDB();
    $inputQuant = se($_POST, "inputQuant", "", false);
    $query9 = "SELECT desired_quantity FROM Cart where user_id=:userid and product_id=:prodid";
    $stmt = $db->prepare($query9);
    try {
        $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $quantity = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $outputQuant = se($quantity[0], "desired_quantity", "", false);
    if ($inputQuant == $outputQuant) {
        $query5 = "UPDATE Cart set desired_quantity = desired_quantity - 1 Where user_id=:userid and product_id=:prodid";
    } elseif (ctype_digit($inputQuant) && $inputQuant >= 0) {
        $inputQuant = intval($inputQuant);
        $query5 = "UPDATE Cart set desired_quantity = $inputQuant Where user_id=:userid and product_id=:prodid";
    } elseif (ctype_digit($inputQuant) && $inputQuant < 0) {
        $query5 = "UPDATE Cart set desired_quantity = 0 Where user_id=:userid and product_id=:prodid";
    } else {
        $inputQuant = intval($outputQuant);
        $query5 = "UPDATE Cart set desired_quantity = $outputQuant Where user_id=:userid and product_id=:prodid";
    }
    $stmt = $db->prepare($query5); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid, ":prodid" => $prodid]);
        $name = $_POST['name'];
        flash("Subtracted Quantity 1 for ITEM: $name!", "success");
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }

    $query7 = "DELETE FROM Cart WHERE (user_id=:userid) and (desired_quantity <= 0)";
    $stmt = $db->prepare($query7); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid],);
        echo '<script type="text/javascript">$(window).on("load", function() {$("#ShoppingCart").modal("show");});</script>';
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}

if (is_logged_in()) {
    $db = getDB();
    $userid = se(get_user_id(), null, "", false); //User id
    $query3 = "SELECT Cart.product_id, Cart.unit_cost, Cart.desired_quantity, Products.name, Products.id FROM Cart, Products where user_id=:userid and Cart.product_id=Products.id";
    $stmt = $db->prepare($query3); //dynamically generated query
    try {
        $stmt->execute([":userid" => $userid],);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $cartRes = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}
require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php if (is_logged_in()) : ?>
    <input type="image" width="50px" class="float-end p-1" src="./Images/shopping-cart.png" data-bs-toggle="modal" data-bs-target="#ShoppingCart">
    <div class="modal fade" id="ShoppingCart" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"><?php se(ucfirst(get_username()), null, "", true)  ?>'s Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php $sum = 0.00;
                    if (!isset($cartRes)) : ?>
                        <p>Empty Cart</p>
                    <?php else : ?>
                        <?php $sum = 0.00;
                        foreach ($cartRes as $item) : ?>
                            <div class="col">
                                <?php $sum += se($item, "unit_cost", "", false) * se($item, "desired_quantity", "", false); ?>
                                <div class="fs-5 m-4">
                                    <?php if (has_role("Admin")) : ?>
                                        <form method="POST" action="/Project/admin/edit_products.php">
                                            <input type="hidden" name="id" value="<?php se($item, "product_id") ?>">
                                            <input type="submit" class="float-start btn btn-light" value="Edit" name="edit">
                                        </form>
                                    <?php endif ?>
                                    <form method="POST" action="/Project/shop.php">
                                        <input type="hidden" name="prodid" value="<?php se($item, "product_id") ?>">
                                        <input type="hidden" name="name" value="<?php se($item, "name") ?>">
                                        <input type="submit" class=" float-start btn btn-danger" value="Remove" name="remove_product">
                                    </form>
                                    <div class="float-start"> <?php se($item, "name") ?></div>
                                    <div class="float-end  "> $<?php echo se($item, "unit_cost", "", false) * se($item, "desired_quantity", "", false) ?> </div><br><br>
                                    <form method="POST" action="/Project/shop.php">
                                        <input type="hidden" name="name" value="<?php se($item, "name") ?>">
                                        <input type="hidden" name="prodid" value="<?php se($item, "product_id") ?>">
                                        <input type="submit" class="btn btn-secondary" value="-" name="minus"></input>
                                        <input type="number" name="inputQuant" value="<?php se($item, "desired_quantity") ?>">
                                        <input type="submit" class="btn btn-secondary" name="plus" value="+"></input>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <div class="modal-footer fs-5 p-3">
                    <p class="float-start">Subtotal: <?php se($sum) ?></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="/Project/shop.php">
                        <input type="submit" class="m-2 float-start btn btn-danger" value="Empty Cart" name="empty_cart">
                    </form>
                    <form method="POST" action="/Project/shop.php">
                        <button type="submit" class="btn btn-primary float-end" data-bs-dismiss="modal">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>