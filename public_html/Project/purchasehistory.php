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
        $query .= " and Orders.created BETWEEN :start AND :end";
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
    $per_page = 3;
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
    <?php
    if (!isset($countOf)) {
        echo "<div class='message2-info'>None</div>";
    } else { ?>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <?php
            $ordNum = $offset + 1;
            foreach ($countOf as $counter) : ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading<?php se($ordNum) ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php se($ordNum) ?>" aria-expanded="false" aria-controls="flush-collapse<?php se($ordNum) ?>">
                            <?php se($ordNum, ""); ?>
                        </button>
                    </h2>
                    <div id="flush-collapse<?php se($ordNum) ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php se($ordNum) ?>" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="p-5 container justify-content-center h5">
                                <?php
                                $countForPurchase = 0;
                                foreach ($purchaseInfo as $item) :
                                    if (se($item, "order_number", null, false) != se($counter, "order_id", null, false)) {
                                        $countForPurchase += 1;
                                        continue;
                                    }
                                ?>
                                    <div class="float-start m-1">Shipping to location:</div><br>
                                    <div class="float-end m-1"><?php se($item, "address") ?></div><br>

                                    <?php for ($x = 0; $x < (int) se($counter, "countOf", null, false); $x += 1) : ?>
                                        <div class="float-start m-1">Item Purchased: </div><br>
                                        <div class="float-end m-1"> <?php se($purchaseInfo[$countForPurchase], "name") ?></div><br>
                                        <div class="float-start m-1">Quantity:</div><br>
                                        <div class="float-end m-1"> <?php se($purchaseInfo[$countForPurchase], "quantity") ?></div><br>
                                        <div class="float-start m-1">UnitCost:</div><br>
                                        <div class="float-end m-1">$<?php se($purchaseInfo[$countForPurchase], "unit_price") ?></div><br>
                                    <?php
                                        $countForPurchase += 1;
                                    endfor ?>

                                    <div class="float-start m-1">Paid With:</div><br>
                                    <div class="float-end m-1"> <?php se($item, "payment_method") ?></div><br>
                                    <div class="float-start m-1"> Total Amount: </div><br>
                                    <div class="float-end m-1">$<?php se($item, "total_price") ?></div><br>
                                <?php break;
                                endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $ordNum += 1; ?>
            <?php endforeach ?>
        </div>
    <?php require(__DIR__ . "/../../partials/pagination.php");
    } ?>
<?php else : ?>
    <h1>Purchase History</h1>
    <div class="text-center">Purchase items for history!</div>
<?php endif ?>