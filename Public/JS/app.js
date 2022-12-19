//Function that copies text when a button is pushed
function copyText() {
    // Get the text field
    var copyText = document.getElementById("discountcode").value;

    // Copy the text inside the text field
    if(navigator.clipboard.writeText(copyText)) {
        document.getElementById("textbox").innerHTML = "Kortingscode gekopieerd!";
    };
}