$(function(){
    $("#restore form").submit(function(e){
        e.preventDefault();
        $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
        $.post($(this).attr('action'), $(this).serialize()).done(function(data){
            if(data == "0")
                window.location.href = "index.php?done=0&message=Your password has been successfully restored";
            else
                $("#error").text(traduction(data));
            setTimeout(function(){
                $("#error").text("");
            },3000);
        });
    });
});
