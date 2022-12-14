//Opens the dialogbox
$('#add-btn').on("click", function() {
    $('#dialogbox').toggleClass("show-dialog");
});

//Opens the overlay behind dialog
$('#add-btn').on("click", function() {
    $('#overlay').toggleClass("show-overlay");
});

//als het document laad voert de pop-up uit
$( document ).ready(function() {
    if (localStorage.getItem('Pop-upshow') != "TRUE" ){
        $('#pop-upbox').toggleClass("show-dialog");
        $('#kortingscode-pop').toggleClass("show-overlay");
        localStorage.setItem('Pop-upshow', 'TRUE');
    };

});

$('#x').on("click", function() {    
    console.log("click");    
    $('#pop-upbox').removeClass("show-dialog");    
    $('#kortingscode-pop').removeClass("show-overlay");
});

