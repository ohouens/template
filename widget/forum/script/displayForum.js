$(function(){
    var flag = false;
    var flagBis = false;
    var ctrlFlag = false;
    var timer = 0;
    var deadline = 5000;
    var refreshTime = 700;
    var end = false;
    var first = true;
    var state = 0;
    var disable = false;
    var colorSave = $("header").css("background-color");

    if($("#contentStatistic").length){
        $('.emojiable-question').emojiPicker({
            width: '300px',
            height: '200px',
            button: false
        });
        $('#trigger').click(function(e) {
            e.preventDefault();
            $('#areaChat').emojiPicker('toggle');
        });

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
        if($("#contentChat").width() < 800){
            $("header").css("display", "none");
            $(".wrapper").css("padding-top", "0");
            $(".wrapper .children").css("height", "100vh");
            $("footer").css("display", "none");
        }

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
        setInterval(function(){loadLast()}, refreshTime);

        $("body").keyup(function(e){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '27'){
                editOff();
            }
            if(keycode == '17'){
                ctrlFlag = false;
                $("header").css("background-color", colorSave);
            }
        });
        $("body").keydown(function(e){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '17'){
                ctrlFlag = true;
                $("header").css("background-color", "#191919");
            }
        });
        $("#sendChat textarea").on("keyup paste change",function(){
            var str = $(this).val();
            res = str.replace(/(https?:\/\/)?onisowo.com\/(index.php)?\?thread=(\w{40})(&request=3)?/, "$3");
            $(this).val(res);
        });
        $("#sendChat textarea").keypress(function(e){
            flag = true;
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var content = this.value;
                var caret = getCaret(this);
                if(e.shiftKey){
                    e.stopPropagation();
                    $(this).selectRange(caret);
                }else{
                    e.preventDefault();
                    flag = false;
                    save1 = $("#contentChat form textarea").val();
                    save2 = $("#contentChat form #voteBlock input").val();
                    if($(".cache").css("display") != "inline-block"){
                        $.post($("#contentChat form").attr('action'), $("#contentChat form").serialize()).done(function(data){
                            $("#contentChat form textarea").val('');
                            $("#contentChat form #voteBlock input").val('');
                            if(data != "0"){
                                $("#contentChat form textarea").val(save1);
                                $("#contentChat form #voteBlock input").val(save2);
                            }
                        });
                    }else{
                        $.post("index.php?thread=0&request=43", $("#contentChat form").serialize()).done(function(data){
                            $("#contentChat form textarea").val('');
                            $("#contentChat form #voteBlock input").val('');
                            if(data == "0"){
                                editOff();
                                loadChat();
                            }else{
                                $("#contentChat form textarea").val(save1);
                                $("#contentChat form #voteBlock input").val(save2);
                            }
                        });
                    }
                }
            }
            timer = 0;
        });

        $("#addAction").click(function(e){
            e.preventDefault();
            if(ctrlFlag){
                $("textarea.kid").css("display", "none");
                $("#voteBlock.kid").css("display", "block");
                $("#send").css("display", "inline-block");
                $("#trigger").css("display", "none");
                disable = true;
                state = 2;
            }else{
                if(state == 2){
                    $("#voteBlock.kid").css("display", "none");
                    $("textarea.kid").css("display", "block");
                    $("#send").css("display", "none");
                    $("#trigger").css("display", "inline-block");
                    disable = false;
                }else{
                    var ta = $("#contentChat form textarea").val();
                    if(ta.substring(0,11) != "~$ declare "){
                        $("#contentChat form textarea").val('~$ declare \n'+ta);
                        $("#contentChat form textarea").focus();
                    }
                }
                state = 0;
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
            if(disable)
                return;
            if(confirm("Do you want to delete this post ?")){
                $.post("index.php?thread=0&request=42", $("#contentChat form").serialize()).done(function(data){
                    if(data == "0"){
                        var c = $("#sendChat input[name='cursor']").val();
                        $('.answer[num="'+c+'"]').css("display", "none");
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
        });

        $("#notifyVote button").click(function(){
            var b = $(this);
            var number = $("#sendChat input[name='cursor']").val();
            $.get("index.php?thread="+$("#contentChat").attr('num')+"&request=9&notify="+$(this).text(), function(data){
                if(data == 0){
                    b.addClass("selected");
                    $.get("index.php?thread="+number+"&request=10", function(refresh){
                        $("#contentChat #buffer").html(refresh);
                        $('.answer[num="'+number+'"] .lock').html($("#contentChat #buffer .lock").html());
                        $("#contentChat #buffer").html("");
                    });
                }
            });
        });
    }

    function getCaret(el) {
        if (el.selectionStart) {
            return el.selectionStart;
        } else if (document.selection) {
            el.focus();
            var r = document.selection.createRange();
            if (r == null) {
                return 0;
            }
            var re = el.createTextRange(), rc = re.duplicate();
            re.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', re);
            return rc.text.length;
        }
        return 0;
    }

    $.fn.selectRange = function(start, end) {
        if(end === undefined) {
            end = start;
        }
        return this.each(function() {
            if('selectionStart' in this) {
                this.selectionStart = start;
                this.selectionEnd = end;
            } else if(this.setSelectionRange) {
                this.setSelectionRange(start, end);
            } else if(this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        });
    };

    function longPress(){
        $(".answer").click(function(){
            if($(this).find(".lock").length){
                var lock = $(this).find(".lock");
                $("#sendChat input[name='cursor']").val($(this).attr('num'));
                $("#contentChat #sendChat").css('display', 'none');
                $("#contentChat #displayLock").css('display', 'flex');
                if(lock.find(".active.vote").length){
                    $("#notifyVote").css("display", "block");
                    $("#notifyVote #a1").text(lock.find(".active").attr('a1'));
                    $("#notifyVote #a2").text(lock.find(".active").attr('a2'));
                    $("#notifyVote #a3").text(lock.find(".active").attr('a3'));
                    $("#notifyVote #a4").text(lock.find(".active").attr('a4'));
                    $("#notifyVote button").each(function(){
                        if($(this).text() == '')
                            $(this).css('display', 'none');
                        else{
                            $(this).css('display', 'inline-block');
                            if($(this).text() == lock.find(".active.vote").attr("answer"))
                                $(this).addClass("selected");
                        }
                    });
                }
                else $("#notifyVote").css("display", "none");
                if(lock.find(".active.barrier.voted").length)
                    $("#notifyBarrier").addClass("selected");
            }else{
                $("#contentChat #sendChat").css('display', 'inline-block');
                $("#contentChat #displayLock").css('display', 'none');
                if(ctrlFlag)
                    $("#sendChat textarea[name='answer']").val($(this).find('.text').text());
            }
        });
    }

    function editSwitch(){
        $(".answer").dblclick(function(){
            $("#sendChat textarea[name='answer']").val($(this).find('.text').text());
            $("#sendChat input[name='cursor']").val($(this).attr('num'));
            $(".nonCache").css("display", "none");
            $(".cache").css("display", "inline-block");
            $("#sendChat textarea[name='answer']").focus();
        });
    }

    function editOff(){
        $("#contentChat #sendChat").css('display', 'inline-block');
        $("#contentChat #displayLock").css('display', 'none');
        $("#sendChat textarea[name='answer']").val("");
        $("#sendChat input[name='cursor']").val("0");
        $(".nonCache").css("display", "inline-block");
        $(".cache, .nonCache.vide").css("display", "none");
    }

    function loadFirst(){
        var cursor = parseInt($("#history .answer").first().attr("cursor"));
        cursor = cursor + 1;
        var height = $('#last').offset().top;
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&begin='+cursor, function(data){
            $("#contentChat #buffer").html(data);
            if((!first) && parseInt($("#buffer .answer").last().attr('num')) < parseInt($("#history .answer").first().attr('num'))){
                $('#displayChat #history').html($("#contentChat #buffer").html() + $('#displayChat #history').html());
                editSwitch(); longPress();

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
            editSwitch(); longPress();
        });
    }

    function loadChatBis(){
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&temoin='+$("#history .answer").first().attr("num"), function(data){
            $('#displayChat #history').html(data);
            editSwitch(); longPress();
        });
    }

    function loadLast(){
        if(flag)
            timer += refreshTime;
        if(timer >= deadline){
            flag = false;
            timer = 0;
        }
        $.get('index.php?thread='+$("#contentChat").attr('num')+'&request=0&last&flag='+flag, function(data){
            $("#contentChat #buffer").html(data);
            if((!first) && $("#buffer .answer").last().attr('num') != $('#displayChat #end #last').attr('num'))
                loadChatBis();
            if($('#contentChat #buffer #isTyping').length || $('#displayChat #end #last').attr("num") != $("#contentChat #buffer #last").attr("num") || $('#displayChat #end #last .text').html() != $("#contentChat #buffer #last .text").html()){
                if($('#contentChat #buffer #isTyping').length)
                    flagBis = true;
                $('#displayChat #end').html(data);
            }else{
                if(flagBis){
                    flagBis = false;
                    $('#displayChat #end').html(data);
                }
            }
            editSwitch(); longPress();
            if(end || first){
                $('#displayChat #history').css('display', 'block');
                first = false;
                $('#displayChat').animate({
                    scrollTop: $('#last').offset().top + ($('#last').offset().top)*10000
                }, 500);
            }
            $("#contentChat #buffer").html("");
        });
    }
});
