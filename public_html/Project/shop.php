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
    <!-- <script src="./helpers.js"></script> -->
</head>

<body>
    <main>

        <?php require(__DIR__ . "/shopping_cart.php") ?>
        <div class=" message-info">
            Search for an item!
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