<?php require(__DIR__ . "/../../partials/nav.php");

echo var_export($_POST);
if (is_logged_in(true) && isset($_POST["checking-out-order"])) :
    echo var_export($_POST["checking-out-order"]);
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
            $unit_price = intval(se($cartCurr, "unit_cost", null, false));
            $q103 = "INSERT INTO OrderItems (product_id, order_id, quantity, unit_price) VALUES (:productid, :orderid, :quantity, :unit_price)";
            $stmt = $db->prepare($q103);
            $stmt->execute([":productid" => $productid, ":orderid" => $orderid, ":quantity" => $quantity, ":unit_price" => $unit_price]);
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }

    $q104 = "DELETE FROM Cart where user_id=:userid";
    $stmt = $db->prepare($q104);
    try {
        $stmt->execute([":userid" => $userid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
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
?>
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