<?php

include "config.php";

//Variables
$voornaam = cleanInput($_POST["voornaam"]);
$tussenvoegsel = cleanInput($_POST["tussenvoegsel"]); //Niet verplicht veld
$achternaam = cleanInput($_POST["achternaam"]);
$email = cleanInput($_POST["email"]);
$tel = cleanInput($_POST["tel"]);
$adres = cleanInput($_POST["adres"]);
$postcode = cleanInput($_POST["postcode"]);
$woonplaats = cleanInput($_POST["woonplaats"]);

//Get cart
$cart = getCart();

//Check if all required fields are set

if(!empty($voornaam) && !empty($achternaam) && !empty($email) && !empty($adres) && !empty($postcode) && !empty($woonplaats)) {
    //Sla persoonsgegevens op in een array
    $persoonsGegevens = array(
        //Check of een tussenvoegsel is ingevoegd, sla dan volledige naam op onder "naam"
        "naam" => "$voornaam $tussenvoegsel $achternaam" ? !empty($tussenvoegsel) : "$voornaam $achternaam",
        "email" => $email,
        "tel" => $tel,
        "adres" => $adres,
        "postcode" => $postcode,
        "woonplaats" => $woonplaats
    );

    //Get data from cart
    foreach($cart as $productID => $productAmount) {
        //Get single stockitem
        $stockItem = getStockItem($productID, $databaseConnection);
        //Get the quantity
        $stockQuantity = getStockQuantity($productID, $databaseConnection);

        //Check if stockitem has been set
        if(isset($stockItem) && !empty($stockItem)) {
            //Convert array to int
            $voorraad = (int)$stockQuantity["QuantityOnHand"];
            //Calculate the new product amount
            $nieuweVoorraad = $voorraad - $productAmount;

            //Update query that updates the quantity on hand
            $Query = "UPDATE stockitemholdings
                      SET QuantityOnHand = ?
                      WHERE StockItemID = ?";
            $stmt = $databaseConnection->prepare($Query);
            $stmt->bind_param("ii", $nieuweVoorraad, $productID);
            $stmt->execute();

            //Save the cart after changing the quantity
            saveCart($cart);
            //Doorlinken naar IDeal
            header("location: https://www.ideal.nl/demo/qr/?app=ideal");
            exit();
        } else {
            print("ERROR: Stockitem variable not set!");
        }
    }
} else {
    print("ERROR: Not all values set!");
}

?>