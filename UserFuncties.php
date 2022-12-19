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

function registerUser($persoonsGegevens, $password_hashed, $databaseConnection){
    extract($persoonsGegevens); //Splits inhoud array op in aparte variabelen
    /*Bestaande variabelen:
    $naam $email $tel $adres $postcode $woonplaats*/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //Exception reporter
    mysqli_begin_transaction($databaseConnection);

    try {
        //define customerID
        $statement = mysqli_prepare($databaseConnection, "
                SELECT MAX(CustomerID) + 1 AS CstId -- Fetch highest known ID and increase by 1, save as CstId
                FROM customers_new;");
        mysqli_stmt_execute($statement);
        $Result = mysqli_stmt_get_result($statement);
        $customerID = mysqli_fetch_column($Result); //Fetch result from SQL query, save into customerID

        //customerID
        $statement = mysqli_prepare($databaseConnection, "SET @CstId = ?;");
        mysqli_stmt_bind_param($statement, 'i', $customerID);
        mysqli_stmt_execute($statement);
        //naam
        $statement = mysqli_prepare($databaseConnection, "SET @name = ?;");
        mysqli_stmt_bind_param($statement, 's', $naam);
        mysqli_stmt_execute($statement);
        //email
        $statement = mysqli_prepare($databaseConnection, "SET @email = ?;");
        mysqli_stmt_bind_param($statement, 's', $email);
        mysqli_stmt_execute($statement);
        //tel
        $statement = mysqli_prepare($databaseConnection, "SET @tel = ?;");
        mysqli_stmt_bind_param($statement, 's', $tel);
        mysqli_stmt_execute($statement);
        //adres
        $statement = mysqli_prepare($databaseConnection, "SET @adres = ?;");
        mysqli_stmt_bind_param($statement, 's', $adres);
        mysqli_stmt_execute($statement);
        //postcode
        $statement = mysqli_prepare($databaseConnection, "SET @postcode = ?;");
        mysqli_stmt_bind_param($statement, 's', $postcode);
        mysqli_stmt_execute($statement);
        //woonplaats
        $statement = mysqli_prepare($databaseConnection, "SET @plaats = ?;");
        mysqli_stmt_bind_param($statement, 's', $woonplaats);
        mysqli_stmt_execute($statement);
        //password
        $statement = mysqli_prepare($databaseConnection, "SET @password = ?;");
        mysqli_stmt_bind_param($statement, 's', $password_hashed);
        mysqli_stmt_execute($statement);

        mysqli_query($databaseConnection, "
        INSERT INTO customers_new
                (
                 CustomerID,
                 CustomerName,
                 AccountOpenedDate,
                 EmailAddress,
                 IsPermittedToLogon,
                 HashedPassword,
                 PhoneNumber,
                 AddressLine,
                 AddressPostalCode,
                 AddressCity,
                 ValidFrom,
                 ValidTo
                )
                
                VALUES
                (
                 @CstId,
                 @name,
                 CURRENT_DATE,
                 @email,
                 1,
                 @password,
                 @tel,
                 @adres,
                 @postcode,
                 @plaats,
                 CURRENT_TIMESTAMP,
                 '9999-12-31 23:59:59'
                );");

        mysqli_commit($databaseConnection);
        mysqli_free_result($Result);

        return $customerID;
    } catch(mysqli_sql_exception $exception){
        mysqli_rollback($databaseConnection);
        //die(var_dump($customerID));
        throw $exception;
    }

}