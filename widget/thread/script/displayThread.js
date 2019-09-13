$(function(){
    if($("#contentWriter").length){
        $.get('?thread='+$("#contentWriter").attr('num')+'&request=6', function(data){
            $("#contentWriter").html(data);
            initSpecial();
        });
    }

    function initSpecial(){
        $(".special").each(function(){
            var tmp = $(this);
            $.get("index.php?thread="+$(this).attr('thread')+"&request=7&user="+$(this).attr('num'), function(data){
                tmp.html(data);
            });
        });
    }

    if($(this).width() < 800)
        $(".flickery").flickity({
            draggable: false,
            groupCells: true,
            initialIndex: 1
        });
});
