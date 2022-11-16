<?php
include "header.php";
?>
<?php
$cart = getCart(); //Haal het winkelmandje op
if(!empty($cart)){ //Check of het winkelmandje leeg is
    ?>
<section class="s-cart" id="CenteredContent">

    <div class="cart-wrapper">
        <h1 class="s-heading">Winkelmandje</h1>
    </div>
    <div class="Cart">
    <?php

    //TEST: Handmatig product geforceerd in cart array
//    $cart[1] = 1;
//    $cart[12] = 1;

    foreach ($cart as $productID => $productAmount) {
        $StockItem = getStockItem($productID, $databaseConnection); //Haal de gegevens op van huidige productID en sla op in een array
        $StockItemImage = getStockItemImage($productID, $databaseConnection); //Haal foto(s) op van huidige productID en sla op in array
        /*Foto's zijn opgeslagen in een 3D array, elke foto heeft een key beginnend bij 0, als value een array met key ImagePath en als value het pad naar de foto*/
        // foreach ($StockItem as $test1 => $test2) { //DEBUG, laat alle gegevens van een product zien
        //     print("$test1 => $test2 <br>");
        // }
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

            <div class="CartItem">
                <div class="image-wrapper">
                    <img src="Public/StockItemIMG/<?php print $productImage; ?>" class="product-image">
                </div>
                <div class="meta-text">
                    <p class="art-tekst">Artikelnummer: <?php print($StockItem['StockItemID'])?></p>
                    <h2 class="art-type-tekst"><?php print($StockItem['StockItemName'])?></h2>
                </div>
                <div class="price-text">
                    <h3 class="art-geld"><?php print(sprintf("€%.2f", $StockItem['SellPrice']))?></h3>
                    <p class="art-tekst">Prijs inclusief BTW</p>
                </div>
            </div>

            <?php
    }
?>
</div>

<form method="post">
    <div class="btn-wrapper">
        <input type="submit" name="clearCartBTN" class="btn-style add-margin btn-small lighter" value="Winkelmandje leegmaken">
        <input type="submit" name="PayCartBTN" class="btn-style add-margin" value="Afrekenen">
    </div>
</form>

</section>

<?php
if(isset($_POST['clearCartBTN'])){
    $cart = array();
    saveCart($cart);
    echo "<script> location.href='cart.php'; </script>";
}
} else{
    print('<h2 id="ProductNotFound">Oeps, je winkelmandje is leeg!</h2>');
}
include "footer.php";
?>