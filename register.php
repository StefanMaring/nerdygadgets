<?php
include "header.php";

//If a user is logged in, dont allow them to see the reigster page
if($userLoggedIn == TRUE) {
    header("location: afrekenen.php");
    exit();
}

?>

<section class="s-register" id="CenteredContent">
    <div class="register-wrapper">
    <h1 class="s-heading">Registreer hier!</h1>
        <form action="register-db.php" method="post">
            <input class="stand-input" type="text" required id="voornaam" name="voornaam" placeholder="*Voornaam">
            <input class="small-input" type="text"  id="tussenvoegsel" name="tussenvoegsel" placeholder="Tussenvoegsel"><br><br>
            <input class="stand-input" type="text" required id="achternaam" name="achternaam" placeholder="*Achternaam"><br><br>
            <input class="stand-input" type="email" required id="email" name="email" placeholder="*Email"><br><br>
            <input class="stand-input" type="tel" required id="tel" name="tel" placeholder="*Telefoonnummer (0612345678)"><br><br>
            <input class="stand-input" type="password" required id="password" name="password" placeholder="*Wachtwoord"><br><br>
            <input class="stand-input" type="password" required id="repeat_password" name="repeat_password" placeholder="*Herhaal Wachtwoord"><br><br>
            <input class="stand-input" type="text" required id="adres" name="adres" placeholder="*Straat + huisnummer">
            <input class="small-input" type="text" required id="postcode" name="postcode" pattern=".{6,7}" placeholder="*Postcode"><br><br>
            <input class="stand-input" type="text" required id="woonplaats" name="woonplaats" placeholder="*Woonplaats"><br><br>
            <input type="hidden" name="faxnumber" id="faxnumber">
            <p class="notice">* is een vereist veld.</p>
            <input type="submit" value="Verzend" class="btn-style">
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
        </form>
    </div>
</section>

<script>document.title = "Nerdygadgets - Registreer hier!";</script>

<?php
include "footer.php";
?>