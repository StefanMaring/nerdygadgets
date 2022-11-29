<?php

session_start();
include "database.php";
include "CartFuncties.php";

function cleanInput($input) {
    strip_tags($input);
    htmlspecialchars($input);
}

?>