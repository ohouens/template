$(function(){
    $.getScript('script/utils.js');

    $("#createThread #submit").click(function(){
        $('#erreurCreate').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "erreur";
        $.post('index.php?thread&request=1', $("#createThread form").serialize()).done(function(data){
            if(data == "0"){
				window.location.replace('index.php?thread=last');
			}else{
				if(data == "10")dire = "incorrect title";
				if(data == "11")dire = "description too short or too long";
				$('#erreurCreate').text(dire);
				setTimeout(function(){
					$('#erreurCreate').text("");
				},3000);
			}
        });
    });

    $("#createThread .select span").click(function(){
        $('#createThread form').attr('origin', $(this).attr("action"));
        $("#createThread .select span").attr("class", "");
        $(this).attr("class", "selected");
        $.get('index.php?origin=createThread&getObject='+$(this).attr("action"), function(data){
            $("#createThread form").html(data);
            if($("#createThread form img").length)
                initUpload();
        });
        $("#createThread button").css("display", "inline");
        $("#createThread form").css("display", "block");
    });

    function initUpload(){
        $("#createThread form img").click(function(){
            $('#createThread form input[type="file"]').trigger('click');
            $('#createThread form input[type="file"]').change(function(){
                readURL(this, "#createThread form img");
            });
        });
    }
});
