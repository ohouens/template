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
                if($(this).scrollTop() == 0)
                    loadFirst();
            }
        });

        loadChat();
        setInterval(function(){loadLast()}, 1000);

        $("#sendChat textarea").on("keyup paste change",function(){
            var str = $(this).val();
            res = str.replace(/(https?:\/\/)?onisowo.com\/(index.php)?\?thread=(\w{40})(&request=3)?/, "$3");
            $(this).val(res);
        });
        $("#sendChat textarea").keypress(function(e){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                    e.preventDefault();
                    $("#send").trigger("click");
            }
        });

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
                    $("#contentChat form textarea").val('');
                    $("#contentChat form #voteBlock input").val('');
                }
            });
        });

        $("#edit").click(function(e){
            e.preventDefault();
            $.post("index.php?thread=0&request=43", $("#contentChat form").serialize()).done(function(data){
                if(data == "0"){
                    $("#contentChat form textarea").val('');
                    $("#contentChat form #voteBlock input").val('');
                    editOff();
                    loadChat();
                }
            });
        });

        $("#delete").click(function(e){
            e.preventDefault();
            if(confirm("Do you want to delete this post ?")){
                $.post("index.php?thread=0&request=42", $("#contentChat form").serialize()).done(function(data){
                    if(data == "0"){
                        $("#contentChat form textarea").val('');
                        $("#contentChat form #voteBlock input").val('');
                        editOff();
                        loadchat();
                    }
                });
            }
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

        $("#notifyVote button").click(function(){
            $.get("index.php?thread="+$("#contentChat").attr('num')+"&request=9&notify="+$(this).text(), function(data){
                if(data == 0)
                    $(this).addClass("selected");
            });
        });
    }

    function editSwitch(){
        $(".answer").dblclick(function(){
            $("#sendChat textarea[name='answer']").text($(this).find('.text').text());
            $("#sendChat input[name='cursor']").val($(this).attr('num'));
            $(".nonCache").css("display", "none");
            $(".cache").css("display", "inline-block");
        });
    }

    function editOff(){
        $("#sendChat textarea[name='answer']").text("");
        $("#sendChat input[name='cursor']").val("0");
        $(".cache").css("display", "none");
        $(".nonCache").css("display", "inline-block");
    }

    function loadFirst(){
        var cursor = parseInt($("#history .answer").first().attr("cursor"));
        cursor = cursor + 1;
        var height = $('#last').offset().top;
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&begin='+cursor, function(data){
            $("#contentChat #buffer").html(data);
            if((!first) && parseInt($("#buffer .answer").last().attr('num')) < parseInt($("#history .answer").first().attr('num'))){
                $('#displayChat #history').html($("#contentChat #buffer").html() + $('#displayChat #history').html());
                editSwitch();

                $("#displayChat").animate({
                    scrollTop: $('#last').offset().top - height
                }, 0);
            }
            $("#contentChat #buffer").html("");
        });
    }

    function loadChat(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0', function(data){
            $('#displayChat #history').html(data);
            editSwitch();
        });
    }

    function loadChatBis(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&temoin='+$("#history .answer").first().attr("num"), function(data){
            $('#displayChat #history').html(data);
            editSwitch();
        });
    }

    function loadLast(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&last', function(data){
            $("#contentChat #buffer").html(data);
            if((!first) && $("#buffer .answer").last().attr('num') != $('#displayChat #end #last').attr('num')){
                loadChatBis();
            }
            $('#displayChat #end').html(data);
            editSwitch();
            if(end || first){
                $('#displayChat #history').css('display', 'block');
                first = false;
                $('#displayChat').animate({
                    scrollTop: $('#last').offset().top + ($('#last').offset().top)*10000
                }, 500);
            }
            if($("#last .lock .active").length){
                $("#contentChat #sendChat").css('display', 'none');
                $("#contentChat #displayLock").css('display', 'flex');
                if($("#last .lock .active.barrier").length) $("#notifyBarrier").css("display", "inline");
                else $("#notifyBarrier").css("display", "none");
                if($("#last .lock .active.vote").length){
                    $("#notifyVote").css("display", "block");
                    $("#notifyVote #a1").text($("#last .lock .active").attr('a1'));
                    $("#notifyVote #a2").text($("#last .lock .active").attr('a2'));
                    $("#notifyVote #a3").text($("#last .lock .active").attr('a3'));
                    $("#notifyVote #a4").text($("#last .lock .active").attr('a4'));
                    $("#notifyVote button").each(function(){
                        if($(this).text() == '')
                            $(this).css('display', 'none');
                        else{
                            $(this).css('display', 'inline-block');
                            if($(this).text() == $("#last .lock .active.vote").attr("answer"))
                                $(this).addClass("selected");
                        }
                    });
                }
                else $("#notifyVote").css("display", "none");
                if($("#last .lock .active.barrier.voted").length)
                    $("#notifyBarrier").addClass("selected");
            }else{
                $("#contentChat #sendChat").css('display', 'block');
                $("#contentChat #displayLock").css('display', 'none');
            }
            $("#contentChat #buffer").html("");
        });
    }
});
