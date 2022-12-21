<?php
include "config.php";

//HONEYPOT

$email = cleanInput($_POST["email"]);
$plaintext_password = cleanInput($_POST["password"]);




//Check if all required fields are set
if(!empty($email) && !empty($plaintext_password)){

    loginUser($email, $plaintext_password, $databaseConnection);
    $_SESSION["feedback"] = ""; //Reset feedback bij reviews
    header("Location: index.php");
    exit();
}