$(document).ready(function(){
    var imagePath = document.body.getAttribute('data-hfnu-images');
    // hide help
    $(".hfnuadmin-help").hide();
    // show help
    $("#hfnuadmin-help").click(function () {
        $(this).toggleClass("active").next().slideToggle("slow");
    });
    //toggle Image
    $("#hfnuadmin-help").toggle(
        function () {
            $(this).find("img").attr({src:imagePath+"delete.png"});
        },
        function () {
            $(this).find("img").attr({src:imagePath+"add.png"});
        }
    );
    //manage tabs
    $("#hfnuadmin-config").tabs();
});