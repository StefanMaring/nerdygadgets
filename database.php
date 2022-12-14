<?php

function resultToArray($result) {
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function connectToDatabase() {
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><h2>Website wordt op dit moment onderhouden.</h2><?php
        die();
    }

    return $Connection;
}

function getHeaderStockGroups($databaseConnection) {
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection) {
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection) {
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            IsChillerStock,
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath,
            StockGroupID
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection) {

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function getStockQuantity($productID, $databaseConnection){
    $Result = null;

    $Query = " 
           SELECT QuantityOnHand
           FROM stockitemholdings
           WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $productID);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function saveCustomer($persoonsGegevens, $databaseConnection){
    extract($persoonsGegevens); //Splits inhoud array op in aparte variabelen
    /*Bestaande variabelen:
    $naam $email $tel $adres $postcode $woonplaats*/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //Exception reporter
    mysqli_begin_transaction($databaseConnection);

    $userData = fetchUserData($email, $databaseConnection);

    if(!$userData){
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

        mysqli_query($databaseConnection, "
        INSERT INTO customers_new
                (
                 CustomerID,
                 CustomerName,
                 EmailAddress,
                 IsPermittedToLogon,
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
                 @email,
                 0,
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
    } elseif($userData["IsPermittedToLogon"] == 0){
        try{
            //define customerID
            $statement = mysqli_prepare($databaseConnection, "
                SELECT CustomerID AS CstId
                FROM customers_new
                WHERE EmailAddress = ?;");
            mysqli_stmt_bind_param($statement, "s", $email);
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

            //UPDATE QUERY HIER
            mysqli_query($databaseConnection, "
        UPDATE customers_new
        SET
                 CustomerName = @name,
                 EmailAddress = @email,
                 IsPermittedToLogon = 0,
                 PhoneNumber = @tel,
                 AddressLine = @adres,
                 AddressPostalCode = @postcode,
                 AddressCity = @plaats
        
            WHERE CustomerID = @CstID;");

            mysqli_commit($databaseConnection);
            mysqli_free_result($Result);

            return $customerID;
        } catch(mysqli_sql_exception $exception){
            mysqli_rollback($databaseConnection);
            throw $exception;
        }
    } else{
        //VERVANG
        print("Er bestaat al een account met dit emailadres!");
        exit();
    }
}

function saveOrder($cart, $customerID, $persoonsGegevens, $databaseConnection){
    extract($persoonsGegevens); //Splits inhoud array op in aparte variabelen
    /*Bestaande variabelen:
    $naam $email $tel $adres $postcode $woonplaats*/
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //Exception reporter
    mysqli_begin_transaction($databaseConnection);

    //CREATE ORDER
    try {
        //define orderID
        $statement = mysqli_prepare($databaseConnection, "
                    SELECT MAX(OrderID) + 1 AS OrderId -- Fetch highest known ID and increase by 1, save as OrderId
                    FROM orders;");
        mysqli_stmt_execute($statement);
        $Result = mysqli_stmt_get_result($statement);
        $orderID = mysqli_fetch_column($Result); //Fetch result from SQL query, save into orderID

        //orderID
        $statement = mysqli_prepare($databaseConnection, "SET @OrderId = ?;");
        mysqli_stmt_bind_param($statement, 'i', $orderID);
        mysqli_stmt_execute($statement);
        //customerID
        $statement = mysqli_prepare($databaseConnection, "SET @CstId = ?;");
        mysqli_stmt_bind_param($statement, 'i', $customerID);
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

        mysqli_query($databaseConnection, "
            INSERT INTO orders
            (
            OrderID,
            CustomerID,
            SalespersonPersonID,
            PickedByPersonID,
            ContactPersonID,
            BackorderOrderID,
            OrderDate,
            ExpectedDeliveryDate,
            IsUndersupplyBackordered,
            LastEditedBy,
            LastEditedWhen,
            AddressLine,
            AddressPostalCode,
            AddressCity
            )
            VALUES
            (
            @OrderId,
            @CstId,
            1,
            1,
            1,
            @OrderId,
            CURRENT_DATE,
            DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY),
            1,
            1,
            CURRENT_TIMESTAMP,
            @adres,
            @postcode,
            @plaats
            )
        ");

        //CREATE ORDERLINES
        foreach($cart as $productID => $productAmount){
            //define orderLineID
            $statement = mysqli_prepare($databaseConnection, "
                        SELECT MAX(OrderLineID) + 1 AS OrderLineId -- Fetch highest known ID and increase by 1, save as OrderId
                        FROM orderlines;");
            mysqli_stmt_execute($statement);
            $Result = mysqli_stmt_get_result($statement);
            $orderLineID = mysqli_fetch_column($Result); //Fetch result from SQL query, save into orderLineID

            //orderLineID
            $statement = mysqli_prepare($databaseConnection, "SET @OrderLineId = ?;");
            mysqli_stmt_bind_param($statement, 'i', $orderLineID);
            mysqli_stmt_execute($statement);

            //orderID
            $statement = mysqli_prepare($databaseConnection, "SET @OrderId = ?;");
            mysqli_stmt_bind_param($statement, 'i', $orderID);
            mysqli_stmt_execute($statement);

            //productID
            $statement = mysqli_prepare($databaseConnection, "SET @ProductId = ?;");
            mysqli_stmt_bind_param($statement, 'i', $productID);
            mysqli_stmt_execute($statement);

            //productAmount
            $statement = mysqli_prepare($databaseConnection, "SET @ProductQuantity = ?;");
            mysqli_stmt_bind_param($statement, 'i', $productAmount);
            mysqli_stmt_execute($statement);

            mysqli_query($databaseConnection, "
                INSERT INTO orderlines
                (
                OrderLineID,
                OrderID,
                StockItemID,
                Description,
                PackageTypeID,
                Quantity,
                UnitPrice,
                TaxRate,
                PickedQuantity,
                LastEditedBy,
                LastEditedWhen
                )
                VALUES
                (
                @OrderLineId,
                @OrderId,
                (SELECT StockItemID FROM stockitems WHERE StockItemID = @ProductId),
                (SELECT StockItemName FROM stockitems WHERE StockItemID = @ProductId),
                (SELECT UnitPackageID FROM stockitems WHERE StockItemID = @ProductId),
                @ProductQuantity,
                (SELECT UnitPrice FROM stockitems WHERE StockItemID = @ProductId),
                (SELECT TaxRate FROM stockitems WHERE StockItemID = @ProductId),
                @ProductQuantity,
                1,
                CURRENT_TIMESTAMP
                )
            ");
        }

        mysqli_commit($databaseConnection);
        mysqli_free_result($Result);

    } catch(mysqli_sql_exception $exception){
        mysqli_rollback($databaseConnection);
        throw $exception;
    }

    $_SESSION["korting"] = 0; // Haal korting weg
}

function addReview($databaseConnection, $CustomerID, $klantNaam, $aantalSterren, $beschrijving, $productPagina) {
    $Query = " 
           INSERT INTO reviews (CustomerID, KlantNaam, AantalSterren, Beschrijving, Product)
               VALUES ('$CustomerID','$klantNaam', '$aantalSterren', '$beschrijving', '$productPagina')
           ";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
}
function getReview($databaseConnection, $product) {
    $Result = null;

    $Query = " 
           SELECT * FROM `reviews` WHERE Product = '$product'
           ";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    return $Result;
}

function checkReviews($databaseConnection, $CustomerID, $product) {
    $Result = null;

    $Query = " 
           SELECT * FROM `reviews` WHERE CustomerID = '$CustomerID' AND Product = '$product'
           ";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    return $Result;
}

function deleteReview($databaseConnection, $CustomerID, $product)
{
    $Query = " 
           DELETE FROM `reviews` WHERE CustomerID = '$CustomerID' AND Product = '$product'
           ";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
}

function aanbevelingenItems ($productID, $databaseConnection) {
    $groupID = mysqli_query($databaseConnection, "
        SELECT StockGroupID
        FROM stockitemstockgroups
        WHERE StockItemID = $productID
        LIMIT 1 ;
");
    $groupID = (int)mysqli_fetch_column($groupID);

    $recommendations = mysqli_prepare($databaseConnection, "
        SELECT StockItemID
        FROM  stockitemstockgroups
        WHERE StockGroupID = $groupID AND StockItemID != $productID
        ORDER BY RAND()
        LIMIT 5 ;
    ");
    mysqli_stmt_execute($recommendations);
    $recommendations = mysqli_stmt_get_result($recommendations);
    $recommendations = resultToArray($recommendations);

    return $recommendations;

}

