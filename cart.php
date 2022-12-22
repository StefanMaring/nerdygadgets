<?php
include "header.php";

$cart = getCart(); //Haal het winkelmandje op
$totaalPrijs = 0;


if(!empty($cart)) { //Check of het winkelmandje leeg is

    //Select usedCode value where usedCode equals the input discount code
    if (isset($_POST["korting_btn"])) {
        $kortingscode = cleanInput($_POST["kortingscode"]);

        $kortingSelect = 'SELECT usedCode
        FROM discountcode
        WHERE kortingscode_text = ?';
        $stmt = mysqli_prepare($databaseConnection, $kortingSelect);
        mysqli_stmt_bind_param($stmt, "s", $kortingscode);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) == 1) { //Check if coupon exists
            $couponUsed = mysqli_fetch_column($result);  //Fetch the specific column
            $couponExists = TRUE;
        } else {
            $couponExists = FALSE;
            $_SESSION ["user_notice_message"] = array("Code bestaat niet");
        }

        /*        $kortingcodetextselect = 'SELECT kortingscode_text
            FROM discountcode
            WHERE kortingscode_text = ?';
                $stmt = $databaseConnection->prepare($kortingcodetextselect);
                $stmt->bind_param("s", $kortingscode);
                $stmt->execute();
                $result = $stmt->get_result(); //Get the number of results
                $couponUsed = $result->fetch_column(); //Fetch the specific column*/


        if ($couponExists) {    //Check of couponcode bestaat
            if ($couponUsed == 1){   //Check of couponcode al gebruikt is
                $_SESSION ["user_notice_message"] = array("Code is al gebruikt");
            } else {    //Stel ingevoerde couponcode in op "gebruikt"
                $_SESSION ["user_notice_message"] = array("Code klopt");
                $_SESSION["korting"] = 10;
                $usedcode = 1;
                $kortingUpdate = 'UPDATE discountcode
                      SET usedCode = ?
                      WHERE kortingscode_text = ?';
                $stmt = $databaseConnection->prepare($kortingUpdate);
                $stmt->bind_param("is", $usedcode, $kortingscode);
                $stmt->execute();
            }


        }

    }


?>

<script>
    //Script that submits form whenever the value of the input changes
    function submit() {
        let form = document.getElementById("aantal-form");
        form.submit();
    }
</script>

<section class="s-cart" id="CenteredContent">
    <div class="cart-header">
        <h1 class="s-heading">Winkelmandje</h1>
    </div>
    <div class="Cart">
    <?php

    foreach ($cart as $productID => $productAmount) {
        $StockItem = getStockItem($productID, $databaseConnection); //Haal de gegevens op van huidige productID en sla op in een array
        $StockItemImage = getStockItemImage($productID, $databaseConnection); //Haal foto(s) op van huidige productID en sla op in array

        /*Foto's zijn opgeslagen in een 3D array, elke foto heeft een key beginnend bij 0, als value een array met key ImagePath en als value het pad naar de foto*/
        if(isset($StockItemImage[0])){ //Check of een product foto's heeft
            $productImage = "Public/StockItemIMG/" . $StockItemImage[0]['ImagePath']; //Sla de 1ste foto van een product op in productImage
        } else{
            $productImage = "Public/StockGroupIMG/" . $StockItem['BackupImagePath']; //Gebruik een andere foto als placeholder
        }

        $maxInWinkelmand = preg_replace("/[^0-9]/", "", $StockItem["QuantityOnHand"] ); //maximale voorraad
        ?>
            <br>

            <div class="CartItem">
                <div class="image-wrapper">
                    <img src="<?php print $productImage; ?>" class="product-image">
                </div>
                <div class="meta-text">
                    <p class="art-tekst">Artikelnummer: <?php print($StockItem['StockItemID'])?></p>
                    <h2 class="art-type-tekst"><a class="cart-link" href="view.php?id=<?php echo $productID?>"><?php print($StockItem['StockItemName'])?></a></h2>


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
    <h2><?php print("Totaal prijs: ".sprintf("€%.2f", $totaalPrijs));?></h2>
    <h2><?php if(isset($_SESSION["korting"]) && $_SESSION["korting"] >= 1){
                print("Totaal prijs met korting: " . sprintf("€%.2f", $totaalPrijs*0.9));
    }
        ?></h2>


</div>

<div class="couponForm">
    <form method="post">
        <div class="flex-form">
            <p id="messageNotice">
                <?php
               // $_SESSION ["user_notice_message"] = array("Gelukt");
                //Check if the message array has been set
                if(isset($_SESSION["user_notice_message"])) {
                    //Loop through all messages and print them
                    foreach($_SESSION["user_notice_message"] as $message) {
                        echo $message . "<br>";
                    }
                    //Empty messages array so a user doesn't see them again
                    $_SESSION["user_notice_message"] = array();
                } else {
                    //If no messages are set, set the array as empty
                    $_SESSION["user_notice_message"] = array();
                }
                ?>
            </p>
            <input class="stand-input-korting" type="text" id="kortingscode" name="kortingscode" placeholder="Kortingscode"><br><br>
            <input type="submit" value="Toevoegen" name="korting_btn" class="btn-style addCodeBtn">

        </div>

    </form>
</div>


<div class="btn-wrapper">
    <form method="post">
        <input type="submit" name="clearCartBTN" class="btn-style add-margin btn-small lighter clearBTN" value="Winkelmandje leegmaken">
    </form>
    <?php
        //When a user is logged in, display this button that sends user directly to payment page
        if($userLoggedIn == TRUE) {
            echo '<form method="post" class="full-width">';
            echo '<input type="submit" class="btn-style add-margin full-width" value="Afrekenen" name="PayCartBTN">';
            echo '</form>';
        }
    ?>
    <?php
        //When a user is not logged in, display this button that opens the dialog beneath
        if($userLoggedIn == FALSE) {
            echo '<button name="OpenRegisterDialog" id="OpenRegisterDialog" class="btn-style add-margin full-width">Afrekenen</button>';
        }
    ?>
</div>

<?php
    //If a user is not logged in, display this dialog when above button is clicked
    if($userLoggedIn == FALSE) {
        echo '
        <div class="total-wrap">
            <div class="overlay" id="overlay"></div>
            <div class="register-dialog" id="register-dialog">
                <h4><i class="fa-solid fa-circle-check m-right"></i>Registreren als klant of verder naar bestellen?</h4>
                <div class="choice-wrapper">
                    <form method="post">
                        <input type="submit" class="btn-style transparent" value="Bestellen afronden" name="PayCartBTN">
                    </form>
                    <button class="btn-style full-width"><a href="register.php">Registreren als klant</a></button>
                </div>
            </div>
        </div>
        ';
    }
?>

<script src="Public/JS/app.jquery.js"></script>
<script>document.title = "Nerdygadgets - Winkelmand";</script>

    </div>
</section>

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

    //Start conversiemaatregel 5
    $recs = aanbevelingenItems($StockItem['StockItemID'], $databaseConnection);

    ?>
    <h1 id="CenteredContent">Wij bevelen ook aan:</h1>

    <div class="aanbevelingen" id="CenteredContent">
        <?php
        foreach($recs as $recID => $recArray) {
        $StockItem = getStockItem($recArray['StockItemID'], $databaseConnection); //Haal de gegevens op van huidige productID en sla op in een array
        $StockItemImage = getStockItemImage($recArray['StockItemID'], $databaseConnection); //Haal foto(s) op van huidige productID en sla op in array
//        print_r($StockItem);

        if(isset($StockItemImage[0])){ //Check of een product foto's heeft
            $productImage = "Public/StockItemIMG/" . $StockItemImage[0]['ImagePath']; //Sla de 1ste foto van een product op in productImage
        } else{
            $productImage = "Public/StockGroupIMG/" . $StockItem['BackupImagePath']; //Gebruik een andere foto als placeholder
        }
        ?>
        <div class="aanbeveling">
            <div class="image-fix">
                <img src="<?php echo $productImage; ?>" class="product-image-aanbeveling">
            </div>
            <div class="aanbeveling-meta">
                <h4 class="wrapped-text"><a class="cart-link" href="view.php?id=<?php echo $StockItem["StockItemID"]?>"><?php print($StockItem['StockItemName'])?></a></h4>
                <?php
                print("Prijs: " . sprintf("€%.2f", $StockItem['SellPrice']) . "<br><br>");
                print("Artikelnummer: " . ($StockItem['StockItemID']));
                $recArrayStockItemID = $StockItem["StockItemID"];
                ?>
            </div>
            <div>
                <form method="POST">
                <input class="btn-style-aanbeveling" type="submit" name="addToCartBTN-<?php echo $recArrayStockItemID?>" value="Toevoegen">
                <input type="hidden" name="stockitemid" value="<?php echo $recArrayStockItemID?>">
                </form>
            </div>
        </div>
        <?php } ?>
        <?php
        if(isset($_POST["stockitemid"])) {
            addProductToCart($_POST["stockitemid"]);
            echo "<script> location.href='cart.php'; </script>";
        }
        ?>
    </div>

    <?php

} else{
    print('<h2 id="ProductNotFound">Oeps, je winkelmandje is leeg!</h2>');
}
?>
</section>

<script>document.title = "Nerdygadgets - Winkelmand";</script>

<?php
include "footer.php";
?>