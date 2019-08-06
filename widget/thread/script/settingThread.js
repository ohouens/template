$(function(){
    $("#resume #view").click(function(){
        window.location.href = "index.php?thread="+$("#resume").attr('num');
    });

    $("#resume #save").click(function(){
        $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
        $.post($("#resume form").attr('action'), $("#resume form").serialize()).done(function(data){
            if(data == "0")
                $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
            else
                $("#error").html('<img src="style/icon/fail.png" alt="fail" class="wait" />');
            setTimeout(function(){
                $("#error").text("");
            },3000);
        });
    });

    $("#resume #delete").click(function(){
        if(confirm("Are you sure about this ? This action will be permanent")){
            $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
            $.get("index.php?thread="+$("#resume").attr('num')+"&request=42", function(data){
                if(data == "0"){
                    $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
                    setTimeout(function(){
                        window.location.href = "index.php?thread=none&list";
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
});