<?php
include "header.php";

if($userLoggedIn == FALSE) {
    header("location: index.php");
    exit();
}

//Select user data
$Query = 'SELECT CustomerName, EmailAddress, PhoneNumber, AddressLine, AddressPostalCode, AddressCity
          FROM customers_new
          WHERE CustomerID = ?';
$stmt = $databaseConnection->prepare($Query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="s-account">
    <div class="account-wrapper">
        <div id="CenteredContent">
            <div class="accountWindow">
                <h1>Accountoverzicht</h1>
                <div class="account-info">
                    <?php
                        //Echo all data
                        while($record = $result->fetch_assoc()) {
                            echo '<form method="POST" action="updateUser-db.php" autocomplete="off">';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Naam:</p>';
                                        echo '<input disabled required id="editableField" name="username" class="user-meta-info" value="'.$record["CustomerName"].'">';
                                echo '</div>';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Email:</p>';  
                                        echo '<input disabled required id="editableField" name="email" class="user-meta-info" value="'.$record["EmailAddress"].'">';
                                echo '</div>';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Telefoon:</p>';
                                        echo '<input disabled required id="editableField" name="phone" class="user-meta-info" value="'.$record["PhoneNumber"].'">';
                                echo '</div>';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Adres:</p>';
                                        echo '<input disabled required id="editableField" name="address" class="user-meta-info" value="'.$record["AddressLine"].'">';
                                echo '</div>';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Postcode:</p>';
                                        echo '<input disabled required id="editableField" name="postcode" class="user-meta-info" pattern=".{6,7}" value="'.$record["AddressPostalCode"].'">';
                                echo '</div>';
                                echo '<div class="account-item">';
                                        echo '<p class="user-meta-name">Woonplaats:</p>';
                                        echo '<input disabled required id="editableField" name="city" class="user-meta-info" value="'.$record["AddressCity"].'">';
                                echo '</div>';
                                echo '<div class="flex-btns">';
                                echo '<input type="submit" class="btn-style editSubmitBTN" id="editSubmitBTN" value="Bevestig">';
                            echo '</form>';
                            echo '<div class="edit-btn">';
                                echo '<button class="btn-style btn-square" title="Bewerk je gegevens" id="edit-info-btn" onclick="makeFieldEditable(); return false;"><i class="fa-solid fa-pencil"></i></button>';
                             echo '</div>';
                             echo '</div>';
                        }
                    ?>
                    <p id="messageNotice" class="updateNotice">
                        <?php 
                            //Check if the message array has been set
                            if(isset($_SESSION["user_notice_message"])) {
                                //Loop through all messages and print them
                                foreach($_SESSION["user_notice_message"] as $message) {
                                    echo $message . "<br>";
                                }
                                //Empty messages array so a user doesn't see them again
                                $_SESSION["user_notice_message"] = array();
                            } else {
                                //If no messages are set, set the array as empty
                                $_SESSION["user_notice_message"] = array();
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="Public/JS/app.js"></script>
<script>document.title = "Nerdygadgets - Accountoverzicht";</script>

<?php
include "footer.php";
?>