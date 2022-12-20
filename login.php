<?php
include "header.php";

//Check of gebruiker al is ingelogd
/*NOTE: Het is in principe onmogelijk voor een ingelogde klant om op deze pagina terecht te komen via de website
Dit is alleen een extra check voor het geval ze handmatig op de loginpagina komen*/
if(!$userID == null){
    header("Location: index.php");
    exit();
}
?>

<section class="s-login" id="CenteredContent">

    <div class="LoginWindow">
        <h1 class="s-heading">Inloggen</h1>
        <form action="login-db" method="post">
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*Email"><br><br>
            <input class="stand-input" type="password" required id="password" name="password" placeholder="*Wachtwoord"><br><br>
            <input type="submit" class="btn-style full-width" value="Inloggen" name="LoginBTN">
        </form>
    </div>
</section>

<?php

//DEBUG
print("Gebruiker ingelogd: ");
print($userLoggedIn ? "Ja, $userID" : "Nee");
echo "<br>";
loginUser("jan@pieter.nl", "test", $databaseConnection);
?>


<?php
include "footer.php"
?>