<?php
include "header.php";
?>

<section class="s-cart">
    <div class="cart-wrapper">
        <h1 class="s-heading">Winkelmandje</h1>
    </div>
    <?php
    getCart();
    //TEST: Handmatig product geforceerd in cart array
    $cart[1] = 1;


    foreach($cart as $productID => $productAmount) {
        $StockItem = getStockItem($productID, $databaseConnection);
        $StockItemImage = getStockItemImage($productID, $databaseConnection);

        foreach($StockItem as $test1 => $test2){
            print("$test1 => $test2 <br>");
        }

        print("<br><br>");

        print("ProductID: " . $StockItem['StockItemID']) . "<br>";
        print("Naam: " . $StockItem['StockItemName'] . "<br>");
        print ("Prijs: " . sprintf("€ %.2f", $StockItem['SellPrice']));
    }
        ?>


</section>

<?php
include "footer.php";
?>