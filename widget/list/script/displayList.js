$(function(){
    if($("#threadList").length){
        $.get('index.php?thread='+$("#threadList").attr('num')+'&request=0', function(data){
            $("#threadList").html(data);
            $("#threadList").imagesLoaded()
            .always(function(instance){
                initMasonryB();
            })
            .progress(function(instance, image){
                initMasonryB();
            });
        });
        $.get('index.php?thread='+$("#follow").attr('num')+'&request=4', function(data){
            if(data == "1")
                $("#follow").addClass("selected").text("Unfollow");
            else
                $("#follow").text("Follow");
        });
        $("#follow").click(function(){
            if(!$(this).hasClass("selected")){
                $.get('index.php?thread='+$(this).attr('num')+'&request=3', function(data){
                    if(data == "0"){
                        $("#follow").addClass('selected').text("Unfollow");
                    }
                });
            }else{
                $.get('index.php?thread='+$(this).attr('num')+'&request=3&token', function(data){
                    if(data == "0"){
                        $("#follow").removeClass('selected').text("Follow");
                    }
                });
            }
        });
    }

    function initMasonryB(){
        $("#threadList").masonry({
            columWidh: '.thread',
            itemSelector: '.thread',
            gutter: 5
        });
    }
});
