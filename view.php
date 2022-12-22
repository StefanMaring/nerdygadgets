<?php
include __DIR__ . "/header.php";

$stockItemID = $_GET['id']; // ProductID van de huidige pagina
$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);

//variabelen voor reviews
$_SESSION['CustomerID'] = getUser();
$_SESSION['productPagina'] = "";
$_SESSION['klantNaam'] = FetchUserName($databaseConnection, getUser());
$totaalReviews=0;
$totaalSterren=0;
if(!isset($_SESSION['feedback'])) {
    $_SESSION['feedback']=" ";
}

?>
<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage) && !empty($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>" class="product-image">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <h3>
            <?php
            //als er een review is gegeven; bereken gemiddelde en laat dit zien
            $review = getReview($databaseConnection, $stockItemID);
            if (mysqli_num_rows($review) > 0) {
                while ($row = mysqli_fetch_assoc($review)) {
                    $totaalReviews += 1;
                    $totaalSterren += $row["AantalSterren"];
                }
                $gemiddeldeSterren=round($totaalSterren/$totaalReviews,1);
                print("<h3 id='sterren'>&#9733;</3>"."$gemiddeldeSterren/5");
            }
            ?>
            </h3>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php if($StockItem['QuantityOnHand'] == "Voorraad: 0") {
                                    print("niet beschikbaar"); }
                                else {print sprintf("€%.2f", $StockItem['SellPrice']);} //prijs laten zien ?></b></p>
                        <h6> Inclusief BTW </h6>
                    </div>
                </div>
            </div>
        </div>
        <?php
        //If stock is bigger than 0, display button
        if($StockItem['QuantityOnHand'] != "Voorraad: 0") {

        ?>
            <div class="add-btn-wrp">
                <iframe name="SendingForm" class="hiddenFrame"></iframe>
                <form method="post" id="openDialForm" target="SendingForm">
                    <input type="submit" id="add-btn" name="addToCartBTN" class="add-btn btn-style" value="Toevoegen aan winkelmand">
                </form>
            </div>
        <?php

        }
        ?>

        <script type="text/javascript">
            $(document).ready(function () {
                setInterval(function () {
                    $("#temp").load(window.location.href + " #temp" );
                }, 3000);
            });
        </script>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails'];?></p>
            <div id="temp">
                <p><?php

                    $temp=mysqli_prepare($databaseConnection, "SELECT Temperature FROM coldroomtemperatures WHERE ColdRoomSensorNumber=5");
                    mysqli_stmt_execute($temp);
                    $temp = mysqli_stmt_get_result($temp);
                    $temp = mysqli_fetch_all($temp, MYSQLI_ASSOC);

                    if ($StockItem['IsChillerStock']){
                        $temperatuur = $temp[0]['Temperature'];

                    print ('Temperatuur: ' . $temperatuur . '°C');
                }?></p>
            </div>
        </div>

        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);

            if (is_array($CustomFields)) { ?>
                <table>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) {
                    $SpecName = str_replace("CountryOfManufacture", "Land van herkomst", $SpecName);?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php
                    $CustomFields = str_replace("CountryOfManufacture", "Land van herkomst", $StockItem['CustomFields']);
                    print $CustomFields; ?>.</p>
                <?php
            }
            ?>

        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>

</div>

<div class="overlay" id="overlay"></div>
<div class="dialog" id="dialogbox">
    <h4><i class="fa-solid fa-circle-check m-right"></i>Product toegevoegd aan winkelmand. Verder winkelen of afrekenen?</h4>
    <div class="choice-wrapper">
        <button class="btn-style transparent"><a href="categories.php">Verder winkelen</a></button>
        <div class="Add-button">
            <form action="" method="POST">
                <input type="submit" class="btn-style" name="goToCart" value="Naar de winkelmand">
            </form>
        </div>
    </div>
</div>

<script src="Public/JS/app.jquery.js"></script>
<script>document.title = "Nerdygadgets - <?php echo $StockItem["StockItemName"];?>";</script>
</div>
<?php if($StockItem != null){ ?>
<div id="reviews">
    <div id="reviews-geplaatst">
        <h2>Geplaatste reviews</h2>
            <?php
                $review = getReview($databaseConnection, $stockItemID);
                if (mysqli_num_rows($review) > 0) {
                    // output data van elke row
                    while ($row = mysqli_fetch_assoc($review)) {
                        print('<div id="enkele-review">');
                        for ($i = 0; $i < $row["AantalSterren"]; $i++) {
                            print("<h3 id='sterren'>&#9733;</3>");
                        }
                        $overigeSterren = (5 - $row["AantalSterren"]);
                        for ($i = 0; $i < $overigeSterren; $i++) {
                            print("<h3 id='sterren-niet'>&#9734;</h3>");
                        }
                        print("<h3 id='klant'>");
                        print($row["KlantNaam"]);
                        print("</h3>");
                        print("<h3 id='review-beschrijving'>");
                        print("<br>");
                        print($row["Beschrijving"] . "<br>");
                        //als er ingelogd is en customerID komt overeen met de review; voeg een verwijder knop toe
                        if(getUser() != NULL) {
                        if ($row["KlantNaam"] == $_SESSION['klantNaam']) {
                            print('<form method="post" action="review-verwijder.php">
        <input type="submit" name="removeReviewBTN" class="btn-style btn-review" value="Verwijder review">
        </form>');
                        }
                        }
                        print("</h3>");
                        print('</div>');

                    }
                } else {
                    echo "Er zijn nog geen reviews voor dit product geplaatst";
                }
            ?>
    </div>
    <div id="reviews-schrijven">
        <h2>Schrijf een review</h2>
        <div class="txt-center">
            <form action="review-plaatsen.php" method="post">
                <div class="rating">
                    <input id="star5" name="star" type="radio" value="5" class="radio-btn hide" />
                    <label for="star5">☆</label>
                    <input id="star4" name="star" type="radio" value="4" class="radio-btn hide" />
                    <label for="star4">☆</label>
                    <input id="star3" name="star" type="radio" value="3" class="radio-btn hide" />
                    <label for="star3">☆</label>
                    <input id="star2" name="star" type="radio" value="2" class="radio-btn hide" />
                    <label for="star2">☆</label>
                    <input id="star1" name="star" type="radio" value="1" class="radio-btn hide" />
                    <label for="star1">☆</label>
                    <textarea id="beschrijving" name="beschrijving" rows="4" cols="40"> </textarea>
                    <input type="submit" class="btn-style add-margin btn-small lighter" value="Review Plaatsen">
                    <div class="clear"></div>
                </div>
            </form>
        <div>

            <?php
                //Check if the message array has been set
                if(isset($_SESSION["feedback"])) {
                    //Loop through all messages and print them
                        print($_SESSION["feedback"]);
                    //Empty messages array so a user doesn't see them again
                    $_SESSION["feedback"] = "";
                } else {
                    //If no messages are set, set the array as empty
                    $_SESSION["feedback"] = ""();
                }
                ?>

        </div>
        </div>
        <?php
        //sla de huidige product pagina op, om hier later naar terug te keren
        $_SESSION["productPagina"] = $stockItemID;
        ?>
    </div>
</div>
<?php } ?>

<?php

//Adds product to cart
if(isset($_POST["addToCartBTN"])) {
    addProductToCart($stockItemID);
}

//Redirects user to cart page
if(isset($_POST["goToCart"])) {
    echo "<script> location.href='cart.php'; </script>";
}

include "footer.php";

?>
