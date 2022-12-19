<?php
include "header.php";
?>

<section class="s-register" id="CenteredContent">
    <div class="register-wrapper">
        <form action="register-db.php" method="post">
            <input class="stand-input" type="text" required id="voornaam" name="voornaam" placeholder="*Voornaam">
            <input class="small-input" type="text"  id="tussenvoegsel" name="tussenvoegsel" placeholder="Tussenvoegsel"><br><br>
            <input class="stand-input" type="text" required id="achternaam" name="achternaam" placeholder="*Achternaam"><br><br>
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*Email"><br><br>
            <input class="stand-input" type="tel" required id="tel" name="tel" placeholder="*Telefoonnummer"><br><br>
            <input class="stand-input" type="password" required id="password" name="password" placeholder="*Wachtwoord"><br><br>
            <input class="stand-input" type="password" required id="repeat_password" name="repeat_password" placeholder="*Herhaal Wachtwoord"><br><br>
            <input class="stand-input" type="text" required id="adres" name="adres" placeholder="*Straat + huisnummer">
            <input class="small-input" type="text" required id="postcode" name="postcode" pattern=".{6,7}" placeholder="*Postcode"><br><br>
            <input class="stand-input" type="text" required id="woonplaats" name="woonplaats" placeholder="*Woonplaats"><br><br>
            <input type="hidden" name="faxnumber" id="faxnumber">
            <input type="submit" value="Verzend" class="btn-style">
            <p class="notice">* is een vereist veld.</p>
        </form>
    </div>
</section>