$(function(){
    $.getScript('script/utils.js');

    $("#createThread #submit").click(function(){
        $('#erreurCreate').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "erreur";
        var $formulaire = $("#createThread form");
        var formdata = (window.FormData) ? new FormData($formulaire[0]) : null;
        var data = (formdata !== null) ? formdata : $formulaire.serialize();
        $.ajax({
            url: $formulaire.attr('action'),
            type: $formulaire.attr('method'),
            contentType: false,
            processData: false,
            data: data,
            success: function(data){
                if(data == "0"){
    				window.location.replace('index.php?thread=last');
    			}else{
    				if(data == "10")dire = "incorrect title";
    				if(data == "11")dire = "description too short or too long";
    				if(data == "12")dire = "A cover is needed";
    				if(data == "13")dire = "file too big, maximum size is 1Mo";
    				if(data == "14")dire = "forbidden file. only png, jpg/jpeg are allowed";
    				if(data == "15")dire = "upload error";
                    if(data == "16")dire = "Incorrect date format";
    				$('#erreurCreate').text(dire);
    				setTimeout(function(){
    					$('#erreurCreate').text("");
    				},3000);
    			}
            },error: function(){
                alert('erreur');
            }
        });
    });

    $("#createThread form").submit(function(e){
        e.preventDefault();
    })

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
