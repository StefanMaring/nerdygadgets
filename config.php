<?php

session_start();
include "database.php";

$_SESSION["winkelmand"] = $winkelwand;

?>