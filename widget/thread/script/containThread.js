$(function(){
    if($("#threadGrid").length){
        $.get('index.php?origin=containThread&getObject=firstPage&withData=Post', function(data){
            $("#threadGrid").html(data);
            $("#threadGrid").imagesLoaded()
            .always(function(instance){
                initMasonry();
            })
            .progress(function(instance, image){
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
