<?php
include "config.php";
//Make sure a logged in user doesn't have their session destroyed etc.
if($userLoggedIn == FALSE) {
    header("location: index.php");
    exit();
} else {
    logoutUser(); //Logout user
    session_unset(); //Free all session variables
    session_destroy(); //Destroy data in a session
    $_SESSION = array(); //Set the session to an empty array
    echo "<script> location.href='index.php'; </script>"; //Redirect back to homepage
    exit();
}
?>