<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/background.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/main.css">
    <title>shop.com</title>
</head>

<body>
    <main>
        <input type="image" width="50px" class="float-end p-1" src="./Images/shopping-cart.png" data-bs-toggle="modal" data-bs-target="#ShoppingCart">
        <div class="modal fade" id="ShoppingCart" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">ShoppingCart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class=" message-info">
            Search for an item and find the lowest price on it today!
        </div>
        <div class="message2-info">FILTER</div>
        <?php require(__DIR__ . "/filter.php") ?>
        <div class="message2-info">
            Products
        </div>
        <?php
        require(__DIR__ . "/show_products.php");
        ?>
        <div class="message2-info">
            Look for items at these websites!
        </div>
        <div class="companies-container">
            <div class="company-container"><a href="https://www.amazon.com" target="_blank"><button class="company-btn c1"></button></a></div>
            <div class="company-container"><a href="https://www.ebay.com" target="_blank"><button class="company-btn c2"></button></a></div>
            <div class="company-container"><a href="https://www.walmart.com" target="_blank"><button class="company-btn c3"></button></a></div>
            <div class="company-container"><a href="https://www.bestbuy.com" target="_blank"><button class="company-btn c4"></button></a></div>
            <div class="company-container"><a href="https://www.shoprite.com" target="_blank"><button class="company-btn c5"></button></a></div>
            <div class="company-container"><a href="https://www.costco.com" target="_blank"><button class="company-btn c6"></button></a></div>
            <div class="company-container"><a href="https://www.lowes.com" target="_blank"><button class="company-btn c7"></button></a></div>
            <div class="company-container"><a href="https://www.homedepot.com" target="_blank"><button class="company-btn c8"></button></a></div>
            <div class="company-container"><a href="https://www.target.com" target="_blank"><button class="company-btn c9"></button></a></div>
        </div>
    </main>
</body>

</html>