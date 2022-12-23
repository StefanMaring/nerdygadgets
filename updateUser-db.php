<?php

include "config.php";

$username = cleanInput($_POST["username"]);
$email = cleanInput($_POST["email"]);
$phone = cleanInput($_POST["phone"]);
$address = cleanInput($_POST["address"]);
$postalcode = cleanInput($_POST["postcode"]);
$city = cleanInput($_POST["city"]);
$faxnumber = cleanInput($_POST["faxnumber"]);

//If bot enters value in this field, set sleep for one hour
if(isset($faxnumber) && !empty($faxnumber)) {
    sleep(3600);
    exit();
}

//Check if all required fields are not empty
if(!empty($username) && !empty($email) && !empty($phone) && !empty($address) && !empty($postalcode) && !empty($city)) {

    //Validate if email is correct
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["user_notice_message"] = array("Email moet een @ bevatten!");
        header("location: account.php");
        exit();
    }

    //Validate if phone is correct
    if(!is_numeric($phone)) {
        $_SESSION["user_notice_message"] = array("Telefoonnummer mag alleen nummers bevatten!");
        header("location: account.php");
        exit();
    }

    //Update customer data
    $updateQuery = 'UPDATE customers_new
                    SET CustomerName = ?, EmailAddress = ?, PhoneNumber = ?, AddressLine = ?, AddressPostalCode = ?, AddressCity = ?
                    WHERE CustomerID = ?';
    $stmt = $databaseConnection->prepare($updateQuery);
    $stmt->bind_param("ssssssi", $username, $email, $phone, $address, $postalcode, $city, $userID);
    $stmt->execute();

    //Notify user when data is updated successfully
    $_SESSION["user_notice_message"] = array("Gegevens succesvol geüpdate!");
    header("location: account.php");
    exit();
} else {
    //Notify user when not all fields have been set
    $_SESSION["user_notice_message"] = array("Lege velden niet toegestaan!");
    header("location: account.php");
    exit();
}

?>