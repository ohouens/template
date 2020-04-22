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
        var count = 500 - l;
        $(this).next().text(count);
    });

    $("#contentFlux form").submit(function(e){
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize()).done(function(data){
            if(data == "0"){
                initFlux();
                $("#addFlux").trigger("click");
                $("#contentFlux form textarea").val('');
                $("#contentFlux form span").text('500');
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

    $.get('index.php?thread='+$("#subscribe").attr('num')+'&request=4', function(data){
        if(data == "1")
            $("#subscribe").addClass("selected").text("Unsubscribe");
        else
            $("#subscribe").text("Subscribe");
    });
    $("#subscribe").click(function(){
        if(!$(this).hasClass("selected")){
            $.get('index.php?thread='+$(this).attr('num')+'&request=3&button', function(data){
                if(data == "0"){
                    $("#subscribe").addClass('selected').text("Unsubscribe");
                }
            });
        }else{
            $.get('index.php?thread='+$(this).attr('num')+'&request=3&token&button', function(data){
                if(data == "0"){
                    $("#subscribe").removeClass('selected').text("Subscribe");
                }
            });
        }
    });
});
