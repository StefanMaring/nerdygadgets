<?php

include "config.php";
$cart = getCart();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>

        <form action="browse.php" method="GET">
            <ul id="ul-class-navigation">
                <li class="search-bar">
                    <input type="search" class="icon search-input" id="searchVal" name="search_string" placeholder="Zoeken...">
                    <div class="fas fa-magnifying-glass search-icon"></div>
                </li>

                <?php if($userLoggedIn == TRUE) {?>
                <li class="user-btn" onclick="openDropdown();">
                    <i class="fa-solid fa-user HrefDecoration"></i>
                </li>

                <?php } else {?>
                    <li class="user-btn">
                        <a href="login.php" class="HrefDecoration"><i class="fa-solid fa-user"></i></a>
                    </li>
                <?php }?>
                
                <li class="cart-btn">
                    <a href="cart.php" class="HrefDecoration"><i class="fa-solid fa-cart-shopping"></i></a>
                </li>
            </ul>
        </form>
        <span class="productCounter"><?php print(count($cart)); ?></span>
        <?php if($userLoggedIn == TRUE) {?>
            <div class="dropdown" id="dropdown">
                <ul>
                    <li><a href="account.php"><i class="fa-solid fa-user"></i>Accountoverzicht</a></li>
                    <li><a href="orders.php"><i class="fa-solid fa-list"></i>Bestellingen</a></li>
                    <li><a href="orders.php"><i class="fa-solid fa-right-from-bracket"></i>Uitloggen</a></li>
                </ul>
            </div>

            <script>
                var dropdown = document.querySelector("#dropdown");
                console.log(dropdown);

                function openDropdown() {
                    dropdown.classList.toggle("show-dropdown");
                }
            </script>
        <?php }?>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


