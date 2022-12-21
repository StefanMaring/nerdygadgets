<?php
session_start();
include "database.php";
include "CartFuncties.php";
include "UserFuncties.php";

$databaseConnection = connectToDatabase();

$userID = getUser(); //Haal userID op

if($userID != null){ //Check of gebruiker ingelogd is
    $userLoggedIn = TRUE;
} else {
    $userLoggedIn = FALSE;
}

//Functie om input te filteren op HTML en andere verdrachte tekens
function cleanInput($input) {
    strip_tags($input);
    htmlspecialchars($input);
    return $input;
}

?>