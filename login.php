<?php
include "header.php"
?>

<section class="s-login" id="CenteredContent">
    <h1 class="s-heading">Inloggen</h1>
    <div class="LoginWindow">

    </div>
</section>

<?php


print("Gebruiker ingelogd: $userLoggedIn \n");
loginUser("test@test.com", $databaseConnection);


?>


<?php
include "footer.php"
?>