<?php
include "config.php";
//variabelen
$_SESSION['klantNaam'] = FetchUserName($databaseConnection, $_SESSION["userID"]);
$_SESSION['CustomerID'] = getUser();
$productPagina=$_SESSION['productPagina'];
//verwijder review
if(isset($_POST["removeReviewBTN"])) {
    deleteReview($databaseConnection, $_SESSION['CustomerID'], $productPagina);
}
//ga terug naar productpagina
header("location: http://localhost/nerdygadgets/view.php?id=$productPagina");
exit();


