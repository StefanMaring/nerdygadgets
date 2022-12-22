//Opens the dialogbox
$('#add-btn').on("click", function() {
    $('#dialogbox').toggleClass("show-dialog");
    $('body').toggleClass("disable-scroll");
    window.scrollTo(0, 0);

});

//Opens the overlay behind dialog
$('#add-btn').on("click", function() {
    $('#overlay').toggleClass("show-overlay");
});

//When the document loads, a check is done to see if the pop up has not been shown already, otherwise display the popup
$( document ).ready(function() {
    //Check if pop hasn't been shown
    if(localStorage.getItem('Pop-upshow') != "TRUE" ){
        $('#pop-upbox').toggleClass("show-dialog");
        $('#kortingscode-pop').toggleClass("show-overlay");
        //Set the storage item to true, so it won't display next time
        localStorage.setItem('Pop-upshow', 'TRUE');
    };
});

//When a button is clicked the classes of show- are removed and the pop up closes
$('#closingBtn').on("click", function() {
    $('#pop-upbox').removeClass("show-dialog");    
    $('#kortingscode-pop').removeClass("show-overlay");
});

//Opens the register dialog
$('#OpenRegisterDialog').on("click", function() {
    $('#register-dialog').toggleClass("show-dialog");
});

//Opens the overlay behind dialog
$('#OpenRegisterDialog').on("click", function() {
    $('#overlay').toggleClass("show-overlay");
});
