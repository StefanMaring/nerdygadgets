<?php
include "config.php";
//variabelen
$_SESSION['klantNaam'] = FetchUserName($databaseConnection, $_SESSION["userID"]);
$_SESSION['CustomerID'] = getUser();
$productPagina=$_SESSION['productPagina'];
if(isset($_POST["removeReviewBTN"])) {
    deleteReview($databaseConnection, $_SESSION['CustomerID'], $productPagina);
}
header("location: http://localhost/nerdygadgets/view.php?id=$productPagina");
exit();


