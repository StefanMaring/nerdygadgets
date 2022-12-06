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

?>