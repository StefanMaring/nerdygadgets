<?php

include "config.php";

//Variables
$voornaam = cleanInput($_POST["voornaam"]);
$tussenvoegsel = cleanInput($_POST["tussenvoegsel"]);
$achternaam = cleanInput($_POST["achternaam"]);
$email = cleanInput($_POST["email"]);
$tel = cleanInput($_POST["tel"]); //Niet verplicht veld
$adres = cleanInput($_POST["adres"]);
$postcode = cleanInput($_POST["postcode"]);
$woonplaats = cleanInput($_POST["woonplaats"]);


//Check if all required fields are set
if(!empty($voornaam) && !empty($tussenvoegsel) && !empty($achternaam) && !empty($email) && !empty($adres) && !empty($postcode) && !empty($woonplaats)) {

}

?>