$(function(){
    if($("#threadList").length){
        $.get('index.php?thread='+$("#threadList").attr('num')+'&request=0', function(data){
            $("#threadList").html(data);
            $("#threadList").imagesLoaded(function(){
                $("#threadList").masonry({
                    columWidh: '.thread',
                    itemSelector: '.thread',
                    gutter: 3
                });
            });
        });
    }
});
