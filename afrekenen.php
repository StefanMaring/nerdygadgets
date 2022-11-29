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
</form>
<form>
    <input type="button" id="bestellen" name="bestellen" value="Bestelling plaatsen"><br>
    <form method="POST" action="" class="afreken-form" id="afreken-form">
        <label for="voornaam">Voornaam:</label><br>
        <input type="text" id="voornaam" name="voornaam"><br>
        <label for="achternaam">Achternaam:</label><br>
        <input type="text" id="achternaam" name="achternaam">
    </form>

<?php
include "footer.php";
?>
