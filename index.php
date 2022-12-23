<?php
include __DIR__ . "/header.php";

$kortingscode = generateRandomString();
$usedcode = 0;

?>
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=93">
                <div class="TextMain">
                    "The Gu" red shirt XML tag t-shirt (Black) M
                </div>
                <ul id="ul-class-price">
                    <li class="HomePagePrice">€30.95</li>
                </ul>
        </div>
        </a>
        <div class="HomePageStockItemPicture"></div>
    </div>
</div>


<div class="overlay" id="kortingscode-pop"></div>
<div class="dialog" id="pop-upbox">
    <h4>Claim hier uw eenmalige kortingscode ter waarde van 10%!</h4>
    <input type="text" disabled id="discountcode" value="<?php echo $kortingscode;?>">
    <p id="textbox"></p>
    <div class="smallBtnWrapper">
        <button id="copyButton" class="btnSmall btn-style" onclick="copyText()"><i class="fa-solid fa-copy"></i></button>
        <button id="closingBtn" class="btnSmall btn-style"><i class="fa-solid fa-circle-xmark"></i></button>
    </div>
</div>

<script src="Public/JS/app.jquery.js"></script>
<script src="Public/JS/app.js"></script>
<script>document.title = "Nerdygadgets - Home";</script>
<?php
include __DIR__ . "/footer.php";

//If a discount code has been entered, insert it into the database
if(isset($kortingscode)) {
    $kortingscodeInsert = "INSERT INTO discountcode (kortingscode_text, usedCode)
                               VALUES (?, ?)";
    $stmt = $databaseConnection->prepare($kortingscodeInsert);
    $stmt->bind_param("si", $kortingscode, $usedcode);
    $stmt->execute();
}
?>








