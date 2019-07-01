$(function(){
    $.getScript('script/utils.js');

    $("#createThread #submit").click(function(){
        $.post('index.php?thread&request=1', $("#createThread form").serialize()).done(function(data){
            alert(data);
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
