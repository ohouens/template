$(function(){
    if($("#threadList").length){
        $.get('index.php?thread='+$("#threadList").attr('num')+'&request=0', function(data){
            $("#threadList").html(data);
            $("#threadList").imagesLoaded(function(){
                $("#threadList").masonry({
                    columWidh: '.thread',
                    itemSelector: '.thread',
                    gutter: 5
                });
            });
        });
        $("#follow").click(function(){
            $.get('index.php?thread='+$(this).attr('num')+'&request=3', function(data){
                if(data == "0"){
                    $("#follow").addClass('selected').text("Unfollow");
                    $("#followers").text(parseInt($("#followers").text())+1);
                }
                if(data == "1"){
                    $("#follow").removeClass("selected").text("Follow");
                    $("#followers").text(parseInt($("#followers").text())-1);
                }
                if(data == "777"){
                    alert('you nedd to sign up');
                }
            });
        });
    }
});
