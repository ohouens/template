$(function(){
    if($("#threadGrid").length){
        $.get('index.php?origin=containThread&getObject=firstPage&withData=Post', function(data){
            $("#threadGrid").html(data);
            initAction();
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
    function initAction(){
        $("#threadGrid .thread").click(function(){
            document.location.href = 'index.php?thread='+$(this).attr('num');
        });
    }
});
