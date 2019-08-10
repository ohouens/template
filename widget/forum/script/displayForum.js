$(function(){
    if($("#contentStatistic").length){
        $.get('index.php?thread='+$("#follow").attr('num')+'=&request=4', function(data){
            if(data == "1")
                $("#follow").addClass("selected").text("Unfollow");
            else
                $("#follow").text("Follow");
        });
        $("#follow").click(function(){
            $.get('index.php?thread='+$(this).attr('num')+'=&request=3', function(data){
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
    if($("#contentChat").length){
        var end = false;
        var first = true;

        $('#displayChat').on('scroll', function() {
            var addition = $(this).scrollTop() + $(this).innerHeight() + 1;
            if(addition >= $(this)[0].scrollHeight) {
                end = true;
            }else{
                end = false;
            }
        });

        // loadChat();
        setInterval(function(){loadChat()}, 1000);

        $("#addAction").click(function(e){
            e.preventDefault();
        });

        $("#send").click(function(e){
            e.preventDefault();
            $.post($("#contentChat form").attr('action'), $("#contentChat form").serialize()).done(function(data){
                if(data == "0"){
                    loadChat();
                    $("#contentChat form textarea").val('');
                }
            });
        });

        $("#contentChat form").submit(function(e){
            e.preventDefault();
            $("#send").trigger("click");
        });
    }

    function loadChat(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0', function(data){
            $('#displayChat').html(data);
            if(end || first){
                first = false;
                $('#displayChat').animate({
                    scrollTop: $('#last').offset().top + $('#last').offset().top
                }, 500);
            }
        });
    }
});
