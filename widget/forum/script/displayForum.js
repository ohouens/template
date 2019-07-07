$(function(){
    if($("#contentStatistic").length){
        $.get('index.php?origin=Forum&getObject='+$("#contentWriter").attr('num')+'&withData=User', function(data){
            // alert(data);
            // $("#contentWriter").html(data);
        });
    }
    if($("#contentChat").length){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0', function(data){
            $('#displayChat').html(data);
        });

        $("#addAction").click(function(e){
            e.preventDefault();
        });

        $("#send").click(function(e){
            e.preventDefault();
        });

        $("#contentChat form").submit(function(e){
            e.preventDefault();
            $("#send").trigger("click");
        });
    }
});
