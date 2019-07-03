$(function(){
    if($("#threadGrid").length){
        $.get('index.php?origin=displayThread&getObject=firstPage&withData=Post', function(data){
            $("#threadGrid").html(data);
            initMasonry();
        });
    }

    function initMasonry(){
        $("#threadGrid").masonry({
            columWidh: '.thread',
            itemSelector: '.thread',
        });
    }
});
