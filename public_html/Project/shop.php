<?php
require(__DIR__ . "/../../partials/nav.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="background.css">
    <link rel="stylesheet" href="./css/background.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="icon" href="/Images/Logo-512.png" type="image/png">
    <title>shop.com</title>
    <script src="input-search.js"></script>
</head>

<body>
    <main>
        <div class="message-info">
            Search for an item and find the lowest price on it today!
        </div>
        <?php if (has_role("Admin")) : ?>
            <h1 style="text-align: center;">ADMIN ROLE HAHAHAH! </h1>
        <?php else : ?>
            <h1 style="text-align: center;">NO ADMIN ROLE :-(</h1>
        <?php endif; ?>
        <div class="search-container">
            <div class="search-area">
                <form action="/search" method="get">
                    <input class="input-search" type="search" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" autocomplete="off" name="search">
                </form>
            </div>
        </div>
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