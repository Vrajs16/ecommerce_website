<?php require(__DIR__ . "/../../partials/nav.php");
if (is_logged_in(true)) {
    $db = getDB();
    $userid = se(get_user_id(), null, "", false); //User id

    $col = se($_GET, "col", "", false);
    //allowed list
    if (!in_array($col, ["total", "created"])) {
        $col = "total_price"; //default value, prevent sql injection
    }
    $order = se($_GET, "order", "", false);

    //allowed list
    if (!in_array($order, ["asc", "desc"])) {
        $order = "asc"; //default value, prevent sql injection
    }
    $categoryForPurchase = se($_GET, "category", "", false);
    $startDate = se($_GET, "startDate", "", false);
    $endDate = se($_GET, "endDate", "", false);

    if ($endDate == "") {
        $endDate = date("Y-m-d");
    }
    $basequery110 = "SELECT Products.name ,total_price, payment_method, address , quantity, OrderItems.unit_price, Orders.id as order_number FROM Orders INNER JOIN OrderItems on Orders.id = OrderItems.order_id INNER JOIN Products on Products.id = OrderItems.product_id and ";
    $qcountPerOrder = "SELECT order_id, count(order_id) As countOf from OrderItems INNER JOIN Orders on Orders.id = order_id and ";
    $total_query = "SELECT count(1) as total FROM Orders WHERE ";
    $query = "1=1 ";
    $params = [];
    if (!has_role("Admin")) {
        $query .= " and user_id = :userid";
        $params[":userid"] = (int) $userid;
    }

    if (!empty($categoryForPurchase) && $categoryForPurchase != "na") {
        $query .= " and payment_method = :category";
        $params[":category"] = $categoryForPurchase;
    }
    if (!empty($startDate) && !empty($endDate)) {
        $query .= " and created BETWEEN :start AND :end";
        $params[":start"] = $startDate;
        $params[":end"] = $endDate;
    }
    //apply column and order sort
    $qcountPerOrder .= $query . " Group by order_id";
    if (!empty($col) && !empty($order)) {
        $qcountPerOrder .= " ORDER BY $col $order";
        if ($col == "created") {
            $query .= " ORDER BY Orders.$col $order";
        } else {
            $query .= " ORDER BY $col $order";
        }
    }

    //Paginate function
    $per_page = 2;
    paginate($total_query . $query, $params, $per_page);
    $stmt = $db->prepare($basequery110 . $query); //dynamically generated query
    foreach ($params as $key => $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $type);
    }
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $purchaseInfo = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    $params[":offset"] = $offset;
    $params[":count"] = $per_page;
    $stmt = $db->prepare($qcountPerOrder . " limit :offset, :count"); //dynamically generated query
    foreach ($params as $key => $value) {
        $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($key, $value, $type);
    }
    try {
        $stmt->execute();
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $countOf = $r;
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
}


if (isset($purchaseInfo)) :
    echo '<pre>' . var_export($purchaseInfo, true) . '</pre>';
    if (isset($countOf)) {
        echo '<pre>' . var_export($countOf, true) . '</pre>';
    }
    if (has_role("Admin")) {
        echo "<h1>All Purchase History</h1>";
    } else {
        echo "<h1>Purchase History</h1>";
    } ?>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/background.css">
        <link rel="stylesheet" href="./css/header.css">
        <link rel="stylesheet" href="./css/main.css">
        <title>shop.com</title>
        <!-- <script src="./helpers.js"></script> -->
    </head>
    <div class="message2-info">FILTER</div>
    <script src="https://kit.fontawesome.com/6d0e983cff.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <form class="row row-cols-auto g-3 align-items-center justify-content-center">
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Date-Range</div>
                <input id="startDate" type="date" class="form-control" name="startDate" value="<?php se($startDate) ?>" />
            </div>
            <div class="input-group">
                <div class="input-group-text">To</div>
                <input id="endDate" type="date" class="form-control" name="endDate" value="<?php se($endDate) ?>" />
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Filter By</div>
                <select class="form-control" name="category" value="<?php se($categoryForPurchase); ?>">
                    <option value="na" readonly>Choose Below</option>
                    <option value="cash">Cash</option>
                    <option value="credit card">Credit card</option>
                    <option value="debit card">Debit card</option>
                </select>
            </div>
            <script>
                //quick fix to ensure proper value is selected since
                //value setting only works after the options are defined and php has the value set prior
                document.forms[0].category.value = "<?php (se($categoryForPurchase, "", null, false) == "") ? se("na") : se($categoryForPurchase) ?>";
            </script>
        </div>
        <div class=" col col-md-6">
            <div class="input-group">
                <div class="input-group-text">Sort By</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="col" value="<?php se($col); ?>">
                    <option value="total_price">Total</option>
                    <option value="created">Date</option>
                </select>
                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].col.value = "<?php se($col); ?>";
                </script>
                <select class="form-control" name="order" value="<?php se($order); ?>">
                    <option value="asc">Low-High</option>
                    <option value="desc">High-Low</option>
                </select>

                <script>
                    //quick fix to ensure proper value is selected since
                    //value setting only works after the options are defined and php has the value set prior
                    document.forms[0].order.value = "<?php se($order); ?>";
                </script>
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="submit" class="btn btn-primary" value="Apply" />
            </div>
        </div>
    </form>
    <div class="message2-info">Orders</div>
    <div class="accordion accordion-flush p-3" id="accordionFlushExample">

        <?php
        if (!isset($countOf)) {
            echo "<div class='message2-info'>None</div>";
        } else {
            $ordNum = 1;
            $cid = -1;
            $count = 1;
            $length = count($purchaseInfo);
            $end = true;
            foreach ($purchaseInfo as $item) : ?>
                <?php if ($cid != se($item, "order_number", null, false)) : ?>
                    <?php $cid = se($item, "order_number", null, false) ?>
                    <?php $end = false; ?>
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="flush-heading<?php se($cid, "") ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php se($cid, "") ?>" aria-expanded="false" aria-controls="flush-collapse<?php se($cid, "") ?>">
                                <?php se($ordNum, "");
                                $ordNum += 1; ?>
                            </button>
                        </h2>

                        <div id="flush-collapse<?php se($cid, "") ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php se($cid, "") ?>" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="p-5 container justify-content-center h5">
                                    <div class="float-start m-1">Shipping to location:</div><br>
                                    <div class="float-end m-1"><?php se($item, "address") ?></div><br>
                                <?php endif ?>
                                <?php if (!$end) : ?>
                                    <?php
                                    if ($count < $length) {
                                        ($purchaseInfo[$count]["order_number"] != $cid) ? $end = true : $end = false;
                                    } else {
                                        $end = true;
                                    }
                                    ?>
                                    <div class="float-start m-1">Item Purchased: </div><br>
                                    <div class="float-end m-1"> <?php se($item, "name") ?></div><br>
                                    <div class="float-start m-1">Quantity:</div><br>
                                    <div class="float-end m-1"> <?php se($item, "quantity") ?></div><br>
                                    <div class="float-start m-1">UnitCost:</div><br>
                                    <div class="float-end m-1">$<?php se($item, "unit_price") ?></div><br>
                                <?php endif ?>
                                <?php if ($end) : ?>
                                    <div class="float-start m-1">Paid With:</div><br>
                                    <div class="float-end m-1"> <?php se($item, "payment_method") ?></div><br>
                                    <div class="float-start m-1"> Total Amount: </div><br>
                                    <div class="float-end m-1">$<?php se($item, "total_price") ?></div><br>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php $count += 1; ?>
            <?php endforeach ?>
    </div>
<?php require(__DIR__ . "/../../partials/pagination.php");
        } ?>


<?php else : ?>
    <h1>Purchase History</h1>

    <div class="text-center">Purchase items for history!</div>

    </div>
<?php endif ?>


<!-- <div class="float-start m-1">Paid With:</div><br>
<div class="float-end m-1"> <?php se($item, "payment_method") ?></div><br>
<div class="float-start m-1"> Total Amount: </div><br>
<div class="float-end m-1">$<?php se($item, "total_price") ?></div><br>

</div>
</div>
</div>
</div> -->
<!-- <script>
    $('#endDate').val(new Date().toISOString().slice(0, 10));
</script> -->