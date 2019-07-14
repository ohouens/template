$(function(){
    if($("#contentStatistic").length){
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
        loadChat();

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
        });
    }
});
