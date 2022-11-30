<?php
include "header.php";
$cart = getCart();
$totaalPrijs = $_SESSION['totaalPrijs'];
?>


<div id="CenteredContent" class="flexBox" >
    <div class="achtergrond bezorgadres-breedte">
        <h1 class="default-margin">Bezorgadres</h1>
        <form action="afrekenen-db.php" method="post" class="default-margin">
            <input class="stand-input" type="text" required id="voornaam" name="voornaam" placeholder="*Voornaam">
            <input class="small-input" type="text"  id="tussenvoegsel" name="tussenvoegsel" placeholder="Tussenvoegsel"><br><br>
            <input class="stand-input" type="text" required id="achternaam" name="achternaam" placeholder="*Achternaam"><br><br>
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*Email"><br><br>
            <input class="stand-input" type="tel" required id="tel" name="tel" pattern="[0-9]+-" placeholder="*Telefoonnummer"><br><br>
            <input class="stand-input" type="text" required id="adres" name="adres" placeholder="*Straat + huisnummer">
            <input class="small-input" type="text" required id="postcode" name="postcode" placeholder="*Postcode"><br><br>
            <input class="stand-input" type="text" required id="woonplaats" name="woonplaats" placeholder="*Woonplaats"><br><br>
            <p class="notice">* is een vereist veld.</p>
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

            //Bereken de eindprijs
            $eindPrijs = $verzendkosten + $totaalPrijs;

            //Print de losse prijzen
            print("<p>Prijs: €$totaalPrijs </p>" . "<p>Verzendkosten: €$verzendkosten</p>" . "<p>Totaalprijs: €$eindPrijs</p>")
            ?>
        </div>


        <div class="achtergrond bestellen-hoogte">
            <h2 class="default-margin">Betaalmethode</h2>
                <div class="btn-padding-wrp">
                    <input type="submit" class="bestellen-btn btn-style" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
                </div>
            </form>
        </div>
    </div>
</div>
<script>document.title = "Nerdygadgets - Afrekenen";</script>

<?php
include "footer.php";
?>
