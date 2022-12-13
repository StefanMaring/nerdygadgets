function copyText() {
    // Get the text field
    var copyText = document.getElementById("discountcode").value;
    console.log(copyText);



    // Copy the text inside the text field

    if(navigator.clipboard.writeText(copyText)) {
        document.getElementById("textbox").innerHTML = "Kopie gemaakt";
    };


}