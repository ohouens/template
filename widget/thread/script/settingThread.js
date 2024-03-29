$(function(){
    $.getScript('script/utils.js');

    $("#resume #viewThread").click(function(){
        window.location.href = "index.php?thread="+$("#resume").attr('num');
    });
    $("#resume #saveThread").click(function(){
        $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
        $.post($("#resume form").attr('action'), $("#resume form").serialize()).done(function(data){
            if(data == "00000000000")
                $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
            else
                $("#error").html('<img src="style/icon/fail.png" alt="fail" class="wait" />');
            setTimeout(function(){
                $("#error").text("");
            },3000);
            // $("#error").html(data);
            if(data.charAt(7) == 1 || data.charAt(8) == 1)
                alert("A licence is required to alert or notify.");
        });
    });

    $("#resume form").submit(function(e){
        e.preventDefault();
        $("#resume #saveThread").trigger("click");
    });

    $("#resume #deleteThread").click(function(){
        if(confirm("Are you sure about this ? This action will be permanent")){
            $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
            $.get("index.php?thread="+$("#resume").attr('num')+"&request=42", function(data){
                if(data == "0"){
                    $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
                    setTimeout(function(){
                        if($("#setting").length)
                            window.location.href = "index.php?katalogi=settings&menu=Katalogi";
                        else
                            window.location.href = "index.php?thread=none&settings";
                    },3000);
                }else{
                    $("#error").html('<img src="style/icon/fail.png" alt="fail" class="wait" />');
                    setTimeout(function(){
                        $("#error").text("");
                    },3000);
                }
            });
        }
    });

    $("textarea[name='output'], textarea[name='input'], textarea[name='lock']").on('keyup paste', function(){
        var str = $(this).val();
        res = str.replace(/(https?:\/\/)?onisowo.com\/(index.php)?\?thread=(\w{40})(&request=3)?/, "$3 ");
        $(this).val(res);
    });
});
