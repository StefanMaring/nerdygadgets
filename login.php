<?php
include "header.php";

//Check of gebruiker al is ingelogd
/*NOTE: Het is in principe onmogelijk voor een ingelogde klant om op deze pagina terecht te komen via de website
Dit is alleen een extra check voor het geval ze handmatig op de loginpagina komen*/
if(!$userID == null){
    echo "<script> location.href='index.php'; </script>";
    exit();
}
?>

<section class="s-login" id="CenteredContent">
    <div class="LoginWindow">
        <h1 class="s-heading">Inloggen</h1>
        <form action="login-db.php" method="post">
            <input class="stand-input login-input" type="email" required id="email" name="email" placeholder="Email"><br><br>
            <input class="stand-input login-input" type="password" required id="password" name="password" placeholder="Wachtwoord"><br><br>
            <input type="submit" class="btn-style full-width" value="Inloggen" name="LoginBTN">
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
    <div class="LoginWindow windowTransparent">
        <div class="login-links">
            <a href="register.php">Registreer hier!</a>
            <a href="change-password.php">Wachtwoord vergeten?</a>
        </div>
    </div>
</section>

<script>document.title = "Nerdygadgets - Login";</script>

<?php

//DEBUG
/*print("Gebruiker ingelogd: ");
print($userLoggedIn ? "Ja, $userID" : "Nee");
echo "<br>";*/
//loginUser("jan@pieter.nl", "test", $databaseConnection);
?>


<?php
include "footer.php"
?>