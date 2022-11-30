<?php
include "header.php";
$cart = getCart();
$totaalPrijs = $_SESSION['totaalPrijs'];
?>


<div id="CenteredContent" class="flexBox" >
    <div class="achtergrond bezorgadres-breedte">
        <h1 class="default-margin">Bezorgadres</h1>
        <form action="afrekenen-db.php" method="post" class="default-margin">
            <input class="stand-input" type="text" required id="voornaam" name="voornaam" placeholder="*voornaam">
            <input class="small-input" type="text"  id="tussenvoegsel" name="tussenvoegsel" placeholder="tussenvoegsel"><br><br>
            <input class="stand-input" type="text" required id="achternaam" name="achternaam" placeholder="*achternaam"><br><br>
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*email"><br><br>
            <input class="stand-input" type="tel" required id="tel" name="tel" placeholder="*telefoonnummer"><br><br>
            <input class="stand-input" type="text" required id="adres" name="adres" placeholder="*straat + huisnummer">
            <input class="small-input" type="text" required id="postcode" name="postcode" placeholder="*postcode"><br><br>
            <input class="stand-input" type="text" required id="woonplaats" name="woonplaats" placeholder="*woonplaats"><br><br>
            <p class="notice">* is een vereist veld.</p>
    </div>

    <div class="achtergrond-wrapper">
        <div class="achtergrond">
            <h1 class="default-margin">Overzicht</h1>
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
            <h1 class="default-margin">Betaalmethode</h1>
                <input type="submit" class="bestellen-btn btn-style" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
