<?php

function getUser(){ //Haalt userID van ingelogde gebruiker op
    if(isset($_SESSION['userID'])){ //Check of userID ingesteld is
        $userID = $_SESSION['userID']; //zo ja: haal op
    } else{
        $userID = null; //zo nee, geef NULL terug
    }
    return $userID;
}

//TO-DO: Vervang die() met exit() - , $userPassword
function loginUser($userEmail, $databaseConnection){
    $userID = getUser();
    if ($userID != null) { // Check of gebruiker al ingelogd is
        die("Gebruiker al ingelogd");
    } else {
        $Query = "
        SELECT CustomerID, IsPermittedToLogon, HashedPassword
        FROM customers_new
        WHERE EmailAddress = ?
        ";

        $Statement = mysqli_prepare($databaseConnection, $Query);
        mysqli_stmt_bind_param($Statement, "s", $userEmail);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 0) {
            die("Email bestaat niet");
        } elseif ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
            die("Email bestaat WEL");
        }
    }
}