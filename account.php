<?php
include "header.php";

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
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Naam:</p>';
                                    echo '<p class="user-meta-info">'.$record["CustomerName"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>';
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Email:</p>';  
                                    echo '<p class="user-meta-info">'.$record["EmailAddress"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>';
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Telefoon:</p>';
                                    echo '<p class="user-meta-info">'.$record["PhoneNumber"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>';
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Adres:</p>';
                                    echo '<p class="user-meta-info">'.$record["AddressLine"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>';
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Postcode:</p>';
                                    echo '<p class="user-meta-info">'.$record["AddressPostalCode"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>';
                            echo '<div class="account-item">';
                                    echo '<p class="user-meta-name">Woonplaats:</p>';
                                    echo '<p class="user-meta-info">'.$record["AddressCity"].'</p>';
                                    echo '<button class="btn-style btn-square" id="edit-info-btn"><i class="fa-solid fa-pencil"></i></button>';
                            echo '</div>'; 
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include "footer.php";
?>