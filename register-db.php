<?php
include "config.php";

//HONEYPOT
if(isset($_POST['faxnumber']) && !empty($_POST['faxnumber'])){
    sleep(3600);
    exit();
}

//Check of herhaalde wachtwoord gelijk is aan wachtwoord
if($_POST["password"] != $_POST["repeat_password"]){
    exit("Wachtwoord is niet gelijk!");
}

//Variables
$voornaam = cleanInput($_POST["voornaam"]);
$tussenvoegsel = cleanInput($_POST["tussenvoegsel"]); //Niet verplicht veld
$achternaam = cleanInput($_POST["achternaam"]);
$email = cleanInput($_POST["email"]);
$tel = cleanInput($_POST["tel"]);
$adres = cleanInput($_POST["adres"]);
$postcode = cleanInput($_POST["postcode"]);
$woonplaats = cleanInput($_POST["woonplaats"]);

$plaintext_password = cleanInput($_POST["password"]);
$password_hashed = password_hash($plaintext_password, PASSWORD_DEFAULT);

//Check if all required fields are set
if(!empty($voornaam) && !empty($achternaam) && !empty($email) && !empty($tel) && !empty($adres) && !empty($postcode) && !empty($woonplaats) && !empty($plaintext_password)) {
    //Sla persoonsgegevens op in een array
    $persoonsGegevens = array(
        //Check of een tussenvoegsel is ingevoegd, sla dan volledige naam op onder "naam"
        "naam" => !empty($tussenvoegsel) ? "$voornaam $tussenvoegsel $achternaam" : "$voornaam $achternaam",
        "email" => $email,
        "tel" => $tel,
        "adres" => $adres,
        "postcode" => $postcode,
        "woonplaats" => $woonplaats
    );

    $customerID = registerUser($persoonsGegevens, $password_hashed, $databaseConnection);

    //Link through to IDeal
    header("location: https://www.ideal.nl/demo/qr/?app=ideal");
    exit();
} else {
    print("ERROR: Not all values set!");
}