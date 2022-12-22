<?php
include "header.php";

//If the cart is empty, redirect user back to categories
if(empty($cart)) {
    echo "<script> location.href='cart.php'; </script>";
    exit();
}

$cart = getCart();
$totaalPrijs = $_SESSION['totaalPrijs'];

if($userLoggedIn){
    $userData = fetchUserDataByID($userID, $databaseConnection);

    /*Ik haat wat ik hier doe
    maar het is nodig omdat we het tussenvoegsel niet op zichzelf opslaan ~Wietse
    */
    $naam = explode(" ", $userData["CustomerName"]);
    $voornaam = array_shift($naam);
    $achternaam = array_pop($naam);
    $tussenvoegsels = implode(" ", $naam);
}
?>


<div id="CenteredContent" class="flexBox" >
    <div class="achtergrond bezorgadres-breedte">
        <h1 class="default-margin">Bezorgadres</h1>
        <form action="afrekenen-db.php" method="post" class="default-margin">
            <input class="stand-input" type="text" required id="voornaam" name="voornaam" placeholder="*Voornaam" value="<?php echo $userLoggedIn ? $voornaam : '' ?>">
            <input class="small-input" type="text"  id="tussenvoegsel" name="tussenvoegsel" placeholder="Tussenvoegsel" value="<?php echo $userLoggedIn ? $tussenvoegsels : '' ?>"><br><br>
            <input class="stand-input" type="text" required id="achternaam" name="achternaam" placeholder="*Achternaam" value="<?php echo $userLoggedIn ? $achternaam : '' ?>"><br><br>
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*Email" value="<?php echo $userLoggedIn ? $userData['EmailAddress'] : '' ?>"><br><br>
            <input class="stand-input" type="tel" required id="tel" name="tel" placeholder="*Telefoonnummer (0612345678)" value="<?php echo $userLoggedIn ? $userData['PhoneNumber'] : '' ?>"><br><br>
            <input class="stand-input" type="text" required id="adres" name="adres" placeholder="*Straat + huisnummer" value="<?php echo $userLoggedIn ? $userData['AddressLine'] : '' ?>">
            <input class="small-input" type="text" required id="postcode" name="postcode" pattern=".{6,7}" placeholder="*Postcode" value="<?php echo $userLoggedIn ? $userData['AddressPostalCode'] : '' ?>"><br><br>
            <input class="stand-input" type="text" required id="woonplaats" name="woonplaats" placeholder="*Woonplaats" value="<?php echo $userLoggedIn ? $userData['AddressCity'] : '' ?>"><br><br>
            <p class="notice">* is een vereist veld.</p>
            <p id="messageNotice">
                <?php 
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
    </div>

    <div class="achtergrond-wrapper">
        <div class="achtergrond">
            <h2 class="default-margin">Overzicht</h2>
            <?php
            //Verzendkosten worden niet opgeteld boven de 50 euro
            $verzendkosten = 0;
            if($totaalPrijs < 50) {
                $verzendkosten += 6.95;
            }

            //Bereken de eindprijs met 2 getallen achter de komma
            $eindPrijs = sprintf("%.2f", $verzendkosten + $totaalPrijs);

            //Print de losse prijzen
            print("<p>Prijs: €$totaalPrijs </p>" . "<p>Verzendkosten: €$verzendkosten</p>" . "<p>Totaalprijs: €$eindPrijs</p>")
            ?>
        </div>


        <div class="achtergrond bestellen-hoogte">
            <div class="titel-gif">
                <h2 class="default-margin">Betaalmethode</h2>
                <div class="credcard" class="p methods"><i class="fa-brands fa-ideal fa-flip credcard" style="--fa-animation-duration: 2s;" ></i>
                </div>
            </div>
            <div class="btn-padding-wrp">
                <input type="submit" class="bestellen-btn btn-style" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
                <img class="iDeal-image" src="https://www.ideal.nl/cms/files/iDEAL-Logo-QR_RGB_v1_1024x1024-740x740.png" alt="iDeal image" height="175px" width="175px">
            </div>
            </form>
        </div>
    </div>
</div>
<script>document.title = "Nerdygadgets - Afrekenen";</script>

<?php
include "footer.php";
?>
