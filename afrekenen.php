<?php
include "header.php";
?>
<form method="get">

    <input type="text" id="voornaam" name="voornaam" placeholder="voornaam"><br>
    <input type="text" id="tussenv" name="tussenv" placeholder="tussenvoegsel"><br>
    <input type="text" id="achternaam" name="achternaam" placeholder="achternaam"><br>
    <input type="text" id="email" name="email" placeholder="email"><br>
    <input type="tel" id="tel" name="tel" placeholder="telefoonnummer"><br>
    <input type="text" id="adres" name="adres" placeholder="straat + huisnummer"><br>
    <input type="text" id="postcode" name="postcode" placeholder="postcode"><br>
    <input type="text" id="woonplaats" name="woonplaats" placeholder="woonplaats"><br>
</form>
<form method="get" action="https://www.ideal.nl/demo/qr/?app=ideal">
    <input type="submit" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
</form>

<?php
include "footer.php";
?>
