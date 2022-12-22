<?php

include "config.php";

$username = cleanInput($_POST["username"]);
$email = cleanInput($_POST["email"]);
$phone = cleanInput($_POST["phone"]);
$address = cleanInput($_POST["address"]);
$postalcode = cleanInput($_POST["postcode"]);
$city = cleanInput($_POST["city"]);

if(!empty($username) && !empty($email) && !empty($phone) && !empty($address) && !empty($postalcode) && !empty($city)) {
    $updateQuery = 'UPDATE customers_new
                    SET CustomerName = ?, EmailAddress = ?, PhoneNumber = ?, AddressLine = ?, AddressPostalCode = ?, AddressCity = ?
                    WHERE CustomerID = ?';
    $stmt = $databaseConnection->prepare($updateQuery);
    $stmt->bind_param("ssssssi", $username, $email, $phone, $address, $postalcode, $city, $userID);
    $stmt->execute();

    $_SESSION["user_notice_message"] = array("Gegevens succesvol geüpdate!");
    header("location: account.php");
    exit();
} else {
    $_SESSION["user_notice_message"] = array("Lege velden niet toegestaan!");
    header("location: account.php");
    exit();
}

?>