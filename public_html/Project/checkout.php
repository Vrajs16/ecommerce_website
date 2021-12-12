<?php require(__DIR__ . "/../../partials/nav.php");
if (is_logged_in(true) && isset($_POST["checkout"])) : ?>
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <h2>Checkout form</h2>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Your cart</span>
                        <span class="badge bg-primary rounded-pill">3</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">Product name</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">Second product</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$8</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">Third item</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$5</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (USD)</span>
                            <strong>$20</strong>
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
                                <label id="card-info-title" for="cc-number" class="form-label">Credit card number</label>
                                <input oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Incorrect credit cart format, check again!')" pattern="[0-9]*" name="cc-number" type="text" class="form-control" id="cc-number" placeholder="" required="">
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
                    document.getElementById('card-info-title').innerHTML = 'Credit card number';
                    document.getElementById('cc-number').value = "";
                } else if ($(this).val() == 'Debit card') {
                    document.getElementById('card-info-title').innerHTML = 'Debit card number';
                    document.getElementById('cc-number').value = "";
                } else {
                    document.getElementById('card-info-title').innerHTML = 'Cash Amount';
                    document.getElementById('cc-number').readOnly = true;
                    document.getElementById('cc-number').value = "<?php echo 100 ?>";
                }
            });
        });
    </script>












<?php else :
    flash("You have no items in your cart! Add items to checkout!", "warning");
    die(header("Location: /Project/shop.php"));
    require_once(__DIR__ . "/../../partials/flash.php");
?>
<?php endif ?>