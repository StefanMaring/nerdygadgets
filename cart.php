<?php
include "header.php";
?>
<?php
$cart = getCart(); //Haal het winkelmandje op
$totaalPrijs = 0;
if(!empty($cart)){ //Check of het winkelmandje leeg is
    ?>

    <script>
        function submit() {
            let form = document.getElementById("aantal-form");
            form.submit();
        }
    </script>


<section class="s-cart" id="CenteredContent">
    <div class="cart-wrapper">
        <h1 class="s-heading">Winkelmandje</h1>
    </div>
    <div class="Cart">
    <?php
    foreach ($cart as $productID => $productAmount) {
        $StockItem = getStockItem($productID, $databaseConnection); //Haal de gegevens op van huidige productID en sla op in een array
        $StockItemImage = getStockItemImage($productID, $databaseConnection); //Haal foto(s) op van huidige productID en sla op in array
        //print_r($StockItem);
        /*Foto's zijn opgeslagen in een 3D array, elke foto heeft een key beginnend bij 0, als value een array met key ImagePath en als value het pad naar de foto*/
        /* foreach ($StockItem as $test1 => $test2) { //DEBUG, laat alle gegevens van een product zien
             print("$test1 => $test2 <br>");
         }*/
        // print_r($StockItemImage);

        if(isset($StockItemImage[0])){ //Check of een product foto's heeft
            $productImage = "Public/StockItemIMG/" . $StockItemImage[0]['ImagePath']; //Sla de 1ste foto van een product op in productImage
        } else{
            $productImage = "Public/StockGroupIMG/" . $StockItem['BackupImagePath']; //Gebruik een andere foto als placeholder
        }
        /*print("<br><br>");

        print("ProductID: " . $StockItem['StockItemID']) . "<br>";
        print("Naam: " . $StockItem['StockItemName'] . "<br>");
        print ("Prijs: " . sprintf("€ %.2f", $StockItem['SellPrice']));

        print("<br><br>");*/
        $maxInWinkelmand = preg_replace("/[^0-9]/", "", $StockItem["QuantityOnHand"] ); //maximale voorraad
        ?>
            <br>

            <div class="CartItem">
                <div class="image-wrapper">
                    <img src="<?php print $productImage; ?>" class="product-image">
                </div>
                <div class="meta-text">
                    <p class="art-tekst">Artikelnummer: <?php print($StockItem['StockItemID'])?></p>
                    <h2 class="art-type-tekst"><a class="cart-link" href="view.php?<?php echo $productID?>"><?php print($StockItem['StockItemName'])?></a></h2>


                    <form id="aantal-form" method="POST">
                        <label class="aantal-text">Aantal: </label>
                        <input onchange="submit()" oninput="this.style.width = (this.value.length + 5) + 'ch';" class="aantal-btn" type="number" id="aantal" name="artikelCounter-<?php echo $productID?>" min="1" max="<?php print($maxInWinkelmand);?>" value="<?php print($productAmount); ?>">
                    </form>
                </div>
                <div class="price-text">
                    <h3 class="art-geld"><?php print(sprintf("€%.2f", round($StockItem['SellPrice'], 2) * $productAmount));
                        $totaalPrijs += round($StockItem['SellPrice'], 2)*$productAmount;?></h3> <!--tel huidige afgeronde prijs op bij totaalprijs-->
                    <p class="art-tekst">Prijs inclusief BTW</p>
                    <form method="post">
                        <input type="hidden" name="removeProductID" value="<?php echo $productID?>">
                        <input type="submit" name="removeProductBTN" class="btn-style add-margin btn-delete lighter" value="Verwijder">
                    </form>
                </div>
            </div>


        <?php
        if(isset($_POST['artikelCounter-' . $productID])) { //Check of aantalknop horend bij huidige productID is aangepast
            /*Onderstaande lijn convert de invoer naar een integer voordat het getal verder wordt behandeld
            Dit doet 2 dingen:
            -Haalt nullen aan het begin weg: b.v. "0025" wordt "25"
            -Rond decimalen af naar hele getallen: b.v. "25.5" wordt "26"
            Dit voorkomt ook dat een getal tussen de 0 en 1 de "0-check" omzeilt, het wordt dan automatisch omgezet naar 1*/
            $_POST["artikelCounter-" . $productID] = (int)$_POST["artikelCounter-" . $productID];

            if ($_POST["artikelCounter-" . $productID] > $maxInWinkelmand) { //Check of ingevoerd aantal hoger is dan de voorraad
                $cart[$productID] = $maxInWinkelmand; //Stel het aantal in op het aantal in voorraad
                saveCart($cart); //Sla winkelmandje op
                unset($_POST['artikelCounter-' . $productID]); //"Aantal"knop loslaten
                echo "<script> location.href='cart.php'; </script>"; //Reload de pagina
            }
            elseif($_POST["artikelCounter-" . $productID] <= 0){ //Check of "aantal" invoer 0 of lager is
                $cart[$productID] = 1;
                saveCart($cart); //Sla winkelmandje op
                unset($_POST['artikelCounter-' . $productID]); //"Aantal"knop loslaten
                echo "<script> location.href='cart.php'; </script>"; //Reload de pagina
            } 
            elseif ($_POST["artikelCounter-" . $productID] != $productAmount) { //Check anders of de waarde verschilt van het huidige aantal in winkelmandje (voorkomt onnodige reload)
                $cart[$productID] = $_POST["artikelCounter-" . $productID];
                saveCart($cart);
                unset($_POST['artikelCounter-' . $productID]);
                echo "<script> location.href='cart.php'; </script>";
            }
        }
    }
?>
        <div class="totalPrice">
            <h1><?php print("Totaal prijs: ".sprintf("€%.2f", $totaalPrijs)); ?></h1>
        </div>
</div>


<form method="post">
    <div class="btn-wrapper">
        <input type="submit" name="clearCartBTN" class="btn-style add-margin btn-small lighter" value="Winkelmandje leegmaken">
        <input type="submit" name="PayCartBTN" class="btn-style add-margin" value="Afrekenen">
    </div>
</form>
<script>document.title = "Nerdygadgets - Winkelmand";</script>


<?php

if(isset($_POST['clearCartBTN'])){
    $cart = array();
    saveCart($cart);
    echo "<script> location.href='cart.php'; </script>";
}

if (isset($_POST['removeProductBTN'])) {
    //print('<h1>' . $_POST["removeProductID"] . '</h1>');
    unset($cart[$_POST['removeProductID']]);
    saveCart($cart);
    echo "<script> location.href='cart.php'; </script>";
}

if(isset($_POST['PayCartBTN'])){
        saveCart($cart);
        $_SESSION['totaalPrijs'] = $totaalPrijs;
        echo "<script> location.href='afrekenen.php'; </script>";
} //afreken knop

} else{
    print('<h2 id="ProductNotFound">Oeps, je winkelmandje is leeg!</h2>');
}
include "footer.php";
?>