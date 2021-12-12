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
        echo var_export($cartInfoCurrent);
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