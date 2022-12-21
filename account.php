<?php
include "header.php";

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
            <h1>Accountoverzicht</h1>
            <div class="account-info">
                <?php
                    while($record = $result->fetch_assoc()) {
                       echo '<p class="user-meta-info">'.$record["CustomerName"].'</p>';
                       echo '<p class="user-meta-info">'.$record["EmailAddress"].'</p>';
                       echo '<p class="user-meta-info">'.$record["PhoneNumber"].'</p>';
                       echo '<p class="user-meta-info">'.$record["AddressLine"].'</p>';
                       echo '<p class="user-meta-info">'.$record["AddressPostalCode"].'</p>';
                       echo '<p class="user-meta-info">'.$record["AddressCity"].'</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
include "footer.php";
?>