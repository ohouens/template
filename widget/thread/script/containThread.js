$(function(){
    if($("#threadGrid").length){
        $.get('index.php?origin=containThread&getObject=firstPage&withData=Post', function(data){
            $("#threadGrid").html(data);
            $("#threadGrid").imagesLoaded(function(){
                initMasonry();
            });
        });
    }

    function initMasonry(){
        $("#threadGrid").masonry({
            columWidh: '.thread',
            itemSelector: '.thread',
            gutter: 5
        });
    }
});
