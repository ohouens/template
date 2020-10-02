$(function(){
    $("#buyLicence").click(function(){
        window.location.href = "index.php?store=detail&object=licence";
    });

    if($("#operation").length){
        setTimeout(function(){window.location.href = "index.php";}, 7000);
    }
});
