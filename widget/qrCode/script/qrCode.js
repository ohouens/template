$(function(){
    $.getScript('script/utils.js');
    $(".qrCode").click(function(){
        copyToClipboard(".qrLink");
        $(this).parent().find(".qrInfo").text("link has been copied!");
        var temp = $(this).parent().find(".qrInfo");
        setTimeout(function(){
            temp.text("");
        },3000);
    });

    $(".qrCode").dblclick(function(){
        window.location.href = $(this).parent().find(".qrLink").text();
    });
});
