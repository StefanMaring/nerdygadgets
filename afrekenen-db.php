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
$OrderisSuccesfull = FALSE;

//Check if all required fields are set
if(!empty($voornaam) && !empty($achternaam) && !empty($email) && !empty($tel) && !empty($adres) && !empty($postcode) && !empty($woonplaats)) {

    //If a string doesn't contain numbers, add message to array
    if(!is_numeric($tel)) {
        $_SESSION["user_notice_message"] = array("Dit veld mag alleen nummers bevatten!");
        header("location: afrekenen.php");
        exit();
    } else {
        $result = substr_replace($tel, "-", 2);
        $tel = $result;
    }

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


    if($userLoggedIn){ //Check if user is already logged in
        $customerID = fetchUserData($email, $databaseConnection)["CustomerID"];   //Get CustomerID from logged in user
    } else {
        $customerID = saveCustomer($persoonsGegevens, $databaseConnection); //Saves customer into database, returns assigned ID
    }

    //Saves order + orderlines in database linked to customerID
    saveOrder($cart, $customerID, $persoonsGegevens, $databaseConnection);

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

            //Check if the stock is bigger than 0, otherwise don't start transactions
            if($nieuweVoorraad >= 0) {
                //Update query that updates the quantity on hand
                $Query = "UPDATE stockitemholdings
                SET QuantityOnHand = ?
                WHERE StockItemID = ?";
                $stmt = $databaseConnection->prepare($Query);
                $stmt->bind_param("ii", $nieuweVoorraad, $productID);
                $stmt->execute();

                //If the statement is executed succesfully set orderispayed to true
                if($stmt->execute()) {
                    $OrderisSuccesfull = TRUE;

                    //If OrderisSuccesfull is TRUE empty the cart and save the cart
                    if($OrderisSuccesfull == TRUE) {
                        $cart = array();
                        saveCart($cart);
                    }
                }
            } else {
                print("ERROR: Stock is too low!");
                exit();
            }
        } else {
            print("ERROR: Stockitem variable not set!");
            exit();
        }
    }
    //Link through to IDeal
    header("location: https://www.ideal.nl/demo/qr/?app=ideal");
    exit();
} else {
    print("ERROR: Not all values set!");
    exit();
}

?>