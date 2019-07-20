$(function(){
    $.getScript('script/utils.js');
    initFlux();

    $("#addFlux").click(function(){
        if($(this).hasClass("selected")){
            $("#flux").css("display", "block");
            $("#contentFlux .grand.vide").css("display", "none");
            $(this).removeClass("selected");
        }else{
            $("#flux").css("display", "none");
            $("#contentFlux .grand.vide").css("display", "flex");
            $(this).addClass("selected");
        }
    });

    $("#contentFlux form textarea").on('keyup paste', function(){
        var l = parseInt($(this).val().length);
        var count = 300 - l;
        $(this).next().text(count);
    });

    $("#contentFlux form").submit(function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize()).done(function(data){
            if(data == "0"){
                initFlux();
                $("#addFlux").trigger("click");
            }else{
                $('#erreur').text(traduction(data));
                setTimeout(function(){
                    $('#erreur').text("");
                },3000);
            }
        });
    });

    $("#contentFlux button").click(function(){
        $("#contentFlux form").trigger("submit");
    });

    function initFlux(){
        $.get("index.php?thread="+$("#contentFlux").attr('num')+"&request=5", function(flux){
            $("#fluxLast").html(flux);
        });
    }
});
