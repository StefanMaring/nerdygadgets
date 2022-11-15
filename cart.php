<?php
include "header.php";
?>

<section class="s-cart">
    <div class="cart-wrapper">
        <h1 class="s-heading">Winkelmandje</h1>
    </div>
    <?php
    $cart = getCart();
    //TEST: Handmatig product geforceerd in cart array
//    $cart[1] = 1;
//    $cart[12] = 1;

if(isset($cart)) {
    foreach ($cart as $productID => $productAmount) {
        $StockItem = getStockItem($productID, $databaseConnection); //Haal de gegevens op van huidige productID en sla op in een array
        $StockItemImage = getStockItemImage($productID, $databaseConnection); //Haal foto(s) op van huidige productID en sla op in array
        /*Foto's zijn opgeslagen in een 3D array, elke foto heeft een key beginnend bij 0, als value een array met key ImagePath en als value het pad naar de foto*/
        foreach ($StockItem as $test1 => $test2) { //DEBUG, laat alle gegevens van een product zien
            print("$test1 => $test2 <br>");
        }
        // print_r($StockItemImage);

        if(isset($StockItemImage[0])){ //Check of een product foto's heeft
            $productImage = $StockItemImage[0]['ImagePath']; //Sla de 1ste foto van een product op in productImage
        } else{
            $productImage = "yoda.png"; //Gebruik een andere foto als placeholder
        }
        /*print("<br><br>");

        print("ProductID: " . $StockItem['StockItemID']) . "<br>";
        print("Naam: " . $StockItem['StockItemName'] . "<br>");
        print ("Prijs: " . sprintf("€ %.2f", $StockItem['SellPrice']));

        print("<br><br>");*/
        ?>
            <br>
        <div class="Cart">
            <div class="CartItem">
                <img src="Public/StockItemIMG/<?php print $productImage; ?>">
                <p><?php print($StockItem['StockItemID'])?></p>
                <h2><?php print($StockItem['StockItemName'])?></h2>
                <h3><?php print(sprintf("€ %.2f", $StockItem['SellPrice']))?></h3>
            </div>
        </div>

            <?php
    }
}
        ?>


</section>

<?php
include "footer.php";
?>