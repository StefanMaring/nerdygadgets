<?php
include "config.php";
//variabelen
$_SESSION['CustomerID'] = $_SESSION["userID"];
$_SESSION['feedback'] = "";
$_SESSION['klantNaam'] = FetchUserName($databaseConnection, $_SESSION["userID"]);
$beschrijving = trim(cleanInput($_POST["beschrijving"]));
$sterren = $_POST['star'];
if ($sterren == "1") {
    $aantalSterren = 1;
}elseif ($sterren == "2") {
    $aantalSterren = 2;
}elseif ($sterren == "3") {
    $aantalSterren = 3;
}elseif ($sterren == "4") {
    $aantalSterren = 4;
}elseif ($sterren == "5") {
    $aantalSterren = 5;
}
$productPagina=$_SESSION['productPagina'];
$geplaatsteReviews = checkReviews($databaseConnection, $_SESSION['CustomerID'], $productPagina);
//review sturen naar database als de velden niet leeg zijn
if($_SESSION['userID']!= NULL) {
    if (!empty($_SESSION['klantNaam']) && !empty($beschrijving) && !empty($aantalSterren) && !empty($productPagina)) {
        //checken of deze klant niet al een review bij een dit product heeft
        if (mysqli_num_rows($geplaatsteReviews) == 0) {
            addReview($databaseConnection, $_SESSION['CustomerID'], $_SESSION['klantNaam'], $aantalSterren, $beschrijving, $productPagina);
            $_SESSION['feedback'] = "";
        } else {
            $_SESSION['feedback'] = ("Iedere klant kan per product maar één review achterlaten");
        }
    } else {
        $_SESSION['feedback'] = ("Vul het review formulier volledig in");
    }
    header("location: http://localhost/nerdygadgets/view.php?id=$productPagina");
    exit();
} else {
    $_SESSION['feedback'] = "Log in om een review te plaatsen";
}



