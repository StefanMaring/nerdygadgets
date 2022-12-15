<?php

session_start();
include "database.php";
include "CartFuncties.php";

$databaseConnection = connectToDatabase();

//Functie om input te filteren op HTML en andere verdrachte tekens
function cleanInput($input) {
    strip_tags($input);
    htmlspecialchars($input);
    return $input;
}

//Function that returns a random string with the length of 8, used to generate a discount codes
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>