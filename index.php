<?php
include __DIR__ . "/header.php";


function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$kortingscode = generateRandomString();

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
    <h4>Claim hier uw kortingscode</h4>
    <input type="text" disabled id="discountcode" value="<?php echo $kortingscode;?>">
    <p id="textbox"></p>
    <button id="copybutton" onclick="copyText()"><i class="fa-solid fa-copy"></i></button>
    <button id="x"><i class="fa-solid fa-circle-xmark"></i></button>
</div>

<script src="Public/JS/app.jquery.js"></script>
<script src="Public/JS/app.js"></script>
<script>document.title = "Nerdygadgets - Home";</script>
<?php
include __DIR__ . "/footer.php";
?>






