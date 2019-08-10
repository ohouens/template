$(function(){
    $.getScript('script/utils.js');
    $("#qrCode").click(function(){
        copyToClipboard("#qrLink");
        $("#qrInfo").text("link has been copied!");
        setTimeout(function(){
            $("#qrInfo").text("");
        },3000);
    });

    $("#qrCode").dblclick(function(){
        window.location.href = $(this).attr("src");
    });
});
