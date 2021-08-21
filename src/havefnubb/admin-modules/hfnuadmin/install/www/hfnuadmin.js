$(document).ready(function(){
    var imagePath = document.body.getAttribute('data-hfnu-images');
    // hide help
    $(".hfnuadmin-help").hide();
    // show help
    $("#hfnuadmin-help").click(function () {
        $(this).toggleClass("active").next().slideToggle("slow");
        if ($(this).hasClass("active")) {
            $(this).find("img").attr({src:imagePath+"delete.png"});
        }
        else {
            $(this).find("img").attr({src:imagePath+"add.png"});
        }
    });

    //manage tabs
    $("#hfnuadmin-config").tabs();
});