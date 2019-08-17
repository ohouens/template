$(function(){
    if($("#contentStatistic").length){
        $.get('index.php?thread='+$("#follow").attr('num')+'&request=4', function(data){
            if(data == "1")
                $("#follow").addClass("selected").text("Unfollow");
            else
                $("#follow").text("Follow");
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
    if($("#contentChat").length){
        var end = false;
        var first = true;
        var state = 0;

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
            $("#contentChat form .slide .kid").css("display", "none");
            if(state == 0){
                $("#barrierBlock.kid").css("display", "flex");
                state = 1
            }else if (state == 1) {
                $("#voteBlock.kid").css("display", "block");
                state = 2
            }else{
                $("textarea.kid").css("display", "block");
                state = 0
            }
            $("#contentChat form input[name='state']").val(state);
        });

        $("#send").click(function(e){
            e.preventDefault();
            $.post($("#contentChat form").attr('action'), $("#contentChat form").serialize()).done(function(data){
                if(data == "0"){
                    loadChat();
                    $("#contentChat form textarea").val('');
                    $("#contentChat form #voteBlock input").val('');
                }
            });
        });

        $("#contentChat form").submit(function(e){
            e.preventDefault();
            $("#send").trigger("click");
        });

        $("#notifyBarrier").click(function(){
            $.get("index.php?thread="+$("#contentChat").attr('num')+"&request=9&notify", function(data){
                if(data == 0)
                    $("#notifyBarrier").addClass("selected");
            });
        });
    }

    function loadChat(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0', function(data){
            $('#displayChat').html(data);
            if(end || first){
                first = false;
                $('#displayChat').animate({
                    scrollTop: $('#last').offset().top + ($('#last').offset().top)*10
                }, 500);
            }
            if($("#last .lock .active").length){
                $("#contentChat #sendChat").css('display', 'none');
                $("#contentChat #displayLock").css('display', 'flex');
                if($("#last .lock .active.barrier.voted").length)
                    $("#notifyBarrier").addClass("selected");
            }else{
                $("#contentChat #sendChat").css('display', 'block');
                $("#contentChat #displayLock").css('display', 'none');
            }
        });
    }
});
