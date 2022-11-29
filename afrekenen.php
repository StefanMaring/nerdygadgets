<?php
include "header.php";
?>
<form>

    <input type="text" id="voornaam" name="voornaam" placeholder="voornaam"><br>
    <input type="text" id="tussenv" name="tussenv" placeholder="tussenvoegsel"><br>
    <input type="text" id="achternaam" name="achternaam" placeholder="achternaam"><br>
    <input type="text" id="email" name="email" placeholder="email"><br>
    <input type="number" id="tel" name="tel" placeholder="telefoonnummer"><br>
    <input type="text" id="adres" name="adres" placeholder="straat + huisnummer"><br>
    <input type="text" id="postcode" name="postcode" placeholder="postcode"><br>
    <input type="text" id="woonplaats" name="woonplaats" placeholder="woonplaats"><br>
    <input type="checkbox" id="factuur" name="facuur">
    <label for="factuur">Factuur adres hetzelfde als bezorg adres</label> <br>
</form>
<form>
    <input type="button" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
</form>

<?php
include "footer.php";
?>
