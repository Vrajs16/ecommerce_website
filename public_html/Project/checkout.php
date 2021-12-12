<?php require(__DIR__ . "/../../partials/nav.php");
if (is_logged_in(true) && isset($_POST["checkout"])) :
    $userid = se(get_user_id(), null, "", false); //User id
    $db = getDB();
    $checkoutSumQuery = "SELECT desired_quantity, unit_price, name, description, stock FROM Cart INNER JOIN Products ON Cart.product_id = Products.id where user_id=:userid";
    $stmt = $db->prepare($checkoutSumQuery);

    try {
        $stmt->execute([":userid" => $userid]);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($r) {
            $checkoutSummary = $r;
        }
        $totalAmountOfItems = 0;
        foreach ($checkoutSummary as $item) {
            $totalAmountOfItems += se($item, "desired_quantity", null, false);
        }
    } catch (PDOException $e) {
        flash("<pre>" . var_export($e, true) . "</pre>");
    }
    var_export($_POST);
    foreach ($checkoutSummary as $item) {
        if (intval(se($item, "desired_quantity", null, false)) > intval(se($item, "stock", null, false))) {
            $desired_quan = intval(se($item, "desired_quantity", null, false));
            $stock = intval(se($item, "stock", null, false));
            $name = se($item, "name", null, false);
            flash("We only have $stock $name's in stock. Please lower the quantity from $desired_quan to a maximum $stock $name's that you can buy!", "danger");
            die(header("Location: /Project/shop.php"));
            require_once(__DIR__ . "/../../partials/flash.php");
        }
    }
?>
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <h2>Checkout form</h2>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Your cart</span>
                        <span class="badge bg-primary rounded-pill"><?php se($totalAmountOfItems) ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <?php $amount = 0;
                        foreach ($checkoutSummary as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0"><?php se($item, "name") ?> x ( <strong><span class="text-success"><?php se($item, "desired_quantity") ?></span></strong> )</h6>
                                    <small class="text-muted"><?php se($item, "description") ?></small>
                                </div>
                                <span class="text-muted">$<?php se($item, "unit_price");
                                                            $amount += se($item, "unit_price", null, false) * se($item, "desired_quantity", null, false) ?></span>
                            </li>
                        <?php endforeach ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (USD)</span>
                            <strong class="text-danger">$<?php se($amount) ?></strong>
                        </li>
                    </ul>
                </div>


                <div class="col-md-7 col-lg-8">
                    <h4 class="mb-3">Billing address</h4>
                    <form method="POST" action="/Project/confirmorder.php">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="firstName" class="form-label">First name</label>
                                <input oninput="setCustomValidity('')" pattern="[A-Za-z ]*" oninvalid="this.setCustomValidity('Incorrect firstname, check again')" name="firstName" type="text" class="form-control" id="firstName" placeholder="" value="" required>
                            </div>

                            <div class="col-sm-6">
                                <label for="lastName" class="form-label">Last name</label>
                                <input oninput="setCustomValidity('')" pattern="[A-Za-z ]*" oninvalid="this.setCustomValidity('Incorrect Lastname, check again!')" name="lastName" type="text" class="form-control" id="lastName" placeholder="" value="" required>
                            </div>

                            <div class="col-12">
                                <label for="username" class="form-label">Username</label>
                                <input oninput="setCustomValidity('')" type="text" class="form-control" id="username" placeholder="Username" value="<?php se(ucfirst(get_username())) ?>" disabled>
                            </div>

                            <div class=" col-12">
                                <label for="email" class="form-label">Email</label>
                                <input oninput="setCustomValidity('')" type="email" class="form-control" id="email" value="<?php se(get_user_email()) ?>" disabled>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <input oninput="setCustomValidity('')" pattern="[A-Za-z0-9. ]*" oninvalid="this.setCustomValidity('Incorrect address, check again!')" name="address" type="text" class="form-control" id="address" placeholder="" required>
                            </div>

                            <div class="col-md-5">
                                <label for="country" class="form-label">Country</label>
                                <input oninput="setCustomValidity('')" pattern="[A-Za-z ]*" oninvalid="this.setCustomValidity('Incorrect country, check again!')" name="country" type="text" class="form-control" id="country" placeholder="" required>
                            </div>

                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input oninput="setCustomValidity('')" pattern="[A-Za-z ]*" oninvalid="this.setCustomValidity('Incorrect State, check again!')" name="state" type="text" class="form-control" id="state" placeholder="" required>
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label">Zip</label>
                                <input oninput="setCustomValidity('')" pattern="[0-9]*" oninvalid="this.setCustomValidity('Incorrect Zip Code, check again!')" name="zip" type="text" class="form-control" id="zip" placeholder="" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3">Payment</h4>

                        <div class="my-3">
                            <div class="form-check">
                                <input id="credit" name="paymentMethod" type="radio" value="Credit card" class="form-check-input" checked required>
                                <label class="form-check-label" for="credit">Credit card<span class="text-muted"> (Visa, MasterCard, Amex)</span></label>
                            </div>
                            <div class="form-check">
                                <input id="debit" name="paymentMethod" type="radio" value="Debit card" class="form-check-input" required>
                                <label class="form-check-label" for="debit">Debit card<span class="text-muted"> (Visa, MasterCard, Amex)</span></label>
                            </div>
                            <div class="form-check">
                                <input id="cash" name="paymentMethod" type="radio" value="Cash" class="form-check-input" required>
                                <label class="form-check-label" for="cash">Cash</label>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label id="card-info-title" for="amount" class="form-label">Credit card will be charged:</label>
                                <input oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Incorrect credit cart format, check again!')" value="<?php se($amount) ?>" pattern="[0-9]*" name="amount" type="text" class="form-control" id="amount" placeholder="" readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button class="w-100 btn btn-primary btn-lg" name="checking-out-order" value="submit" type="submit">Continue to checkout</button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="my-5 pt-5">
        </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(function() {
            $('input[name="paymentMethod"]').on('click', function() {
                console.log("hello world");
                if ($(this).val() == 'Credit card') {
                    document.getElementById('card-info-title').innerHTML = 'Credit card will be charged:';
                    document.getElementById('amount').value = "<?php se($amount) ?>";
                } else if ($(this).val() == 'Debit card') {
                    document.getElementById('card-info-title').innerHTML = 'Debit card will be charged:';
                    document.getElementById('amount').value = "<?php se($amount) ?>";
                } else {
                    document.getElementById('card-info-title').innerHTML = 'Amount to pay in cash:';
                    document.getElementById('amount').value = "<?php se($amount) ?>";
                }
            });
        });
    </script>
<?php elseif (is_logged_in()) :
    flash("Please use the checkout button in the shopping cart", "warning");
    die(header("Location: /Project/shop.php"));
    require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php else :
    flash("Login before checking out!", "warning");
    die(header("Location: /Project/shop.php"));
    require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php endif ?>