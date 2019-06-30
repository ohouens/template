$(function(){
    $("#createThread .select span").click(function(){
        $("#createThread .select span").attr("class", "");
        $(this).attr("class", "selected");
        $.get('index.php?origin=createThread&getObject='+$(this).attr("action"), function(data){
            $("#createThread form").html(data);
        });
        $("#createThread button").css("display", "inline");
        $("#createThread form").css("display", "block");
    });
});
