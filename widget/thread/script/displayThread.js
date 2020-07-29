$(function(){
    if($("#contentWriter").length){
        $.get('?thread='+$("#contentWriter").attr('num')+'&request=6', function(data){
            $("#contentWriter").html(data);
            $(".removeU").click(function(e){
                e.preventDefault();
                if(confirm("Do you want to remove this user ?")){
                    window.location.href = $(this).attr("href");
                }
            });
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
        $("div.special.alignement").click(function(){
            var circle = $(this).find(".circle");
            $.get("index.php?thread="+$(this).attr("thread")+"&request=7&customer="+$(this).attr("num")+"&valide", function(data){
                if(data == "0")
                    circle.attr("class", "circle val1");
            });
        });
    }

    if($(this).width() < 800)
        $(".flickery").flickity({
            draggable: false,
            groupCells: true,
            pageDots: false,
            initialIndex: 1
        });
});
