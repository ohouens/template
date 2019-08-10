$(function(){
    $.getScript('script/utils.js');
    $("#qrCode").click(function(){
        copyToClipboard("#qrLink");
    });
});
