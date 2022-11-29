<?php
include "header.php";
$cart = getCart();
$totaalPrijs = $_SESSION['totaalPrijs'];
?>


<div class="bezorgadres" id="CenteredContent">
    <h1 class="default-margin">Bezorgadres</h1>
    <form action="afrekenen-db.php" method="post" class="default-margin">
        <input class="stand-input" type="text" id="voornaam" name="voornaam" placeholder="voornaam">
        <input class="small-input" type="text" id="tussenvoegsel" name="tussenvoegsel" placeholder="tussenvoegsel">
        <input class="stand-input" type="text" id="achternaam" name="achternaam" placeholder="achternaam"><br><br>
        <input class="stand-input" type="text" id="email" name="email" placeholder="email"><br><br>
        <input class="small-input" type="tel" id="tel" name="tel" placeholder="telefoonnummer"><br><br>
        <input class="stand-input" type="text" id="adres" name="adres" placeholder="straat + huisnummer"><br><br>
        <input class="small-input" type="text" id="postcode" name="postcode" placeholder="postcode"><br><br>
        <input class="stand-input" type="text" id="woonplaats" name="woonplaats" placeholder="woonplaats"><br><br>



        <div class="overzicht" id="CenteredContent">
            <h1 class="default-margin">Overzicht</h1>
            <?php
            print("<p>Prijs: €$totaalPrijs </p>" . "<p>Verzendkosten: </p>" . "<p>Totaalprijs: </p>")
            ?>
        </div>

        <div class="betaalmethode" id="CenteredContent">
            <h1 class="default-margin">Betaalmethode</h1>
                <input class="stand-input" type="submit" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
            </form>
        </div>
</div>

<?php
include "footer.php";
?>
