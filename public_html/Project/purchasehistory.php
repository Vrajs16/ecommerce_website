<?php require(__DIR__ . "/../../partials/nav.php");
if (is_logged_in(true)) {
    $db = getDB();
    $userid = se(get_user_id(), null, "", false); //User id
    $name = se($_GET, "name", "", false);
    $col = se($_GET, "col", "", false);
    //allowed list
    if (!in_array($col, ["total", "date"])) {
        $col = "total"; //default value, prevent sql injection
    }
    $order = se($_GET, "order", "asc", false);
    //allowed list
    if (!in_array($order, ["asc", "desc"])) {
        $order = "asc"; //default value, prevent sql injection
    }
    $q110 = "SELECT Products.name ,total_price, payment_method, address , quantity, OrderItems.unit_price, Orders.id as order_number FROM Orders INNER JOIN OrderItems on Orders.id = OrderItems.order_id INNER JOIN Products on Products.id = OrderItems.product_id ";
    if (!has_role("Admin")) {
        $q110 .= " where Orders.user_id =:userid";
        $stmt = $db->prepare($q110); //dynamically generated query
        try {
            $stmt->execute([":userid" => $userid]);
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                $purchaseInfo = $r;
            }
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    } else {
        $stmt = $db->prepare($q110); //dynamically generated query
        try {
            $stmt->execute();
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($r) {
                $purchaseInfo = $r;
            }
        } catch (PDOException $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
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
                <div class="input-group-text">Search</div>
                <input class="form-control" name="name" value="<?php se($name); ?>" />
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <div class="input-group-text">Date-Range</div>
                <input id="startDate" type="date" class="form-control" name="startDate" value="" />
            </div>
            <div class="input-group">
                <div class="input-group-text">To</div>
                <input id="endDate" type="date" class="form-control" name="endDate" value="" />
            </div>
        </div>
        <div class=" col col-md-6">
            <div class="input-group">
                <div class="input-group-text">Sort By</div>
                <!-- make sure these match the in_array filter above-->
                <select class="form-control" name="col" value="<?php se($col); ?>">
                    <option value="total">Total</option>
                    <option value="date">Date</option>
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
    <div class="accordion accordion-flush p-3" id="accordionFlushExample">

        <?php
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
                            Order #<?php se($ordNum, "");
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
<script>
    $('#endDate').val(new Date().toISOString().slice(0, 10));
</script>