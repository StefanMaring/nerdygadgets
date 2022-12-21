<?php
include "config.php";
//variabelen
$_SESSION['klantNaam'] = "Rob Drost";
$_SESSION['CustomerID'] = "123456789";
$productPagina=$_SESSION['productPagina'];
if(isset($_POST["removeReviewBTN"])) {
    deleteReview($databaseConnection, $_SESSION['CustomerID'], $productPagina);
}
header("location: http://localhost/nerdygadget/view.php?id=$productPagina");
exit();


