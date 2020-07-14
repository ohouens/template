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
                if(data.length == 40){
    				window.location.replace('index.php?thread='+data);
    			}else{
    				$('#erreurCreate').text(traduction(data));
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
        $('#createThread #submit').trigger('click');
    })

    $("#createThread .select span").click(function(){
        $('#createThread form').attr('origin', $(this).attr("action"));
        $("#createThread .select span").attr("class", "");
        $(this).attr("class", "selected");
        $.get('index.php?origin=createThread&getObject='+$(this).attr("action"), function(data){
            $("#createThread form").html(data);
            if($("#createThread form img").length)
                initUpload();
            if($("#preview").length)
                initPreview();
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

    function initPreview(){
        $("#createThread form input[name='thread']").on("keyup paste",function(){
            var str = $(this).val();
            res = str.replace(/(https?:\/\/)?onisowo.com\/(index.php)?\?thread=(\w{40})(&request=3)?/, "$3");
            $(this).val(res);
            result = $(this).val().match(/\w{40}/)
            if(result != null){
                $("input[name='list']").val($("input[name='list']").val()+" "+result);
                $(this).val("");
                preview();
            }
        });
    }

    function preview(){
        $.get("index.php?thread=none&request=56&list="+$("input[name='list']").val(), function(data){
            $("#preview").html(data);
        });
    }
});
