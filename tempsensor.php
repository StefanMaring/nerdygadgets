<?php


// connectie met database
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
        die();
    }

    return $Connection;
}

$database = connectToDatabase();


//stuurt random getal met gemeten tijd naar database
while(TRUE){
    $Tijd = date("Y-m-d H:i:s");
    $Temperatuur = rand(100, 400)/100;

    $ColdroomUpdate=mysqli_prepare($database,
        " UPDATE coldroomtemperatures 
                SET RecordedWhen = ?, Temperature = ?, ValidFrom = ? 
                WHERE coldRoomSensorNumber = 5;");
    mysqli_stmt_bind_param($ColdroomUpdate, 'sds', $Tijd , $Temperatuur, $Tijd );
    mysqli_stmt_execute($ColdroomUpdate);

    print($Temperatuur . "\n");
    sleep(3);
}