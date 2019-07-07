$(function(){
    if($("#contentStatistic").length){
        $.get('index.php?origin=Forum&getObject='+$("#contentWriter").attr('num')+'&withData=User', function(data){
            // alert(data);
            // $("#contentWriter").html(data);
        });
    }
    if($("#contentChat").length){
        loadChat();

        $("#addAction").click(function(e){
            e.preventDefault();
        });

        $("#send").click(function(e){
            e.preventDefault();
            $.post($("#contentChat form").attr('action'), $("#contentChat form").serialize()).done(function(data){
                if(data == "0")
                    loadChat();
                    $("#contentChat form textarea").val('');
            })
        });

        $("#contentChat form").submit(function(e){
            e.preventDefault();
            $("#send").trigger("click");
        });
    }
    function loadChat(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0', function(data){
            $('#displayChat').html(data);
        });
    }
});
