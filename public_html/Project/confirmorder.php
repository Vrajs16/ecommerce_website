<?php require(__DIR__ . "/../../partials/nav.php");
if (is_logged_in(true) && isset($_POST["checking-out-order"])) :
    $userid = se(get_user_id(), "", null, false);
    $amount = se($_POST, "amount", null, false);
    $paymentMethod = se($_POST, "paymentMethod", null, false);
    $address = "";
    $address .= se($_POST, "address", null, false) . ", ";
    $address .= se($_POST, "state", null, false) . " ";
    $address .= se($_POST, "zip", null, false) . ", ";
    $address .= se($_POST, "country", null, false);

    $db = getDB();
    $q100 = "INSERT INTO Orders (user_id, total_price, payment_method, address) VALUES (:userid, :amount, :paymentMethod, :address)";
    $stmt = $db->prepare($q100);
    try {
        $stmt->execute([":userid" => $userid, ":amount" => $amount, ":paymentMethod" => $paymentMethod, ":address" => $address]);
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }

    $q101 = "SELECT * FROM Cart WHERE user_id=:userid";
    $stmt = $db->prepare($q101);
    try {
        $stmt->execute([":userid" => $userid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $cartInfoCurrent = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $q102 = "SELECT id from Orders ORDER BY id DESC LIMIT 1";
    $stmt = $db->prepare($q102);
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $orderNum = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $orderId = se($orderNum[0], "id", null, false);
    foreach ($cartInfoCurrent as $cartCurr) {
        try {
            $productid = intval(se($cartCurr, "product_id", null, false));
            $orderid = intval($orderId);
            $quantity = intval(se($cartCurr, "desired_quantity", null, false));
            $unit_price = floatval(se($cartCurr, "unit_cost", null, false));
            $q103 = "INSERT INTO OrderItems (product_id, order_id, quantity, unit_price) VALUES (:productid, :orderid, :quantity, :unit_price)";
            $stmt = $db->prepare($q103);
            $stmt->execute([":productid" => $productid, ":orderid" => $orderid, ":quantity" => $quantity, ":unit_price" => $unit_price]);
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }

    foreach ($cartInfoCurrent as $cartCurr) {
        try {
            $quantity = intval(se($cartCurr, "desired_quantity", null, false));
            $productID = intval(se($cartCurr, "product_id", null, false));
            $q105 = "UPDATE Products set stock = stock - :quantity where id=:product_id";
            $stmt = $db->prepare($q105);
            $stmt->execute([":quantity" => $quantity, ":product_id" => $productID]);
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }

    $q106 = "SELECT Products.name ,total_price, payment_method, address , quantity, OrderItems.unit_price FROM Orders INNER JOIN OrderItems on Orders.id = OrderItems.order_id INNER JOIN Products on Products.id = OrderItems.product_id  where user_id=:userid and Orders.id =:orderid";
    $stmt = $db->prepare($q106);
    try {
        $stmt->execute([":userid" => $userid, ":orderid" => intval($orderId)]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $orderDetails = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }

    $q104 = "DELETE FROM Cart where user_id=:userid";
    $stmt = $db->prepare($q104);
    try {
        $stmt->execute([":userid" => $userid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
?>
    <div class="container-fluid ">
        <h1>Order Confirmation Page</h1> <br>
        <h2 class="text-center">Order Details</h2>
        <div class="p-5 container justify-content-center h5">
            <div class="float-start m-1">
                Shipping to location:
            </div><br>
            <div class="float-end m-1">
                <?php se($orderDetails[0], "address") ?>
            </div><br>
            <?php foreach ($orderDetails as $item) : ?>
                <div class="float-start m-1">Item Purchased: </div><br>
                <div class="float-end m-1"> <?php se($item, "name") ?></div><br>
                <div class="float-start m-1">Quantity:</div><br>
                <div class="float-end m-1"> <?php se($item, "quantity") ?></div><br>
                <div class="float-start m-1">UnitCost:</div><br>
                <div class="float-end m-1">$<?php se($item, "unit_price") ?></div><br>
            <?php endforeach ?>
            <div class="float-start m-1">Paid With:</div><br>
            <div class="float-end m-1"> <?php se($orderDetails[0], "payment_method") ?></div><br>
            <div class="float-start m-1"> Total Amount: </div><br>
            <div class="float-end m-1">$<?php se($orderDetails[0], "total_price") ?></div><br>
        </div>
        <h3 class="text-center">Thank you for your purchase! Please shop again!</h3>
        <div class="col text-center">
            <button class="btn btn-primary p-2" onclick="location.href='/Project/shop.php';">Main Page</button>
        </div>

    </div>


<?php elseif (is_logged_in(true)) :
    flash("Please follow the checkout process from the shopping cart! Thank you!", "warning");
    die(header("Location: /Project/shop.php"));
    require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php else :
    flash("Login before confirming order!", "warning");
    die(header("Location: /Project/shop.php"));
    require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php endif ?>