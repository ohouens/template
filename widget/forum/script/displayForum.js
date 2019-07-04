$(function(){
    if($("#contentCode").length){
        $("#contentCode #qrCode").attr('src', 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://localhost/ohouens/project/onisowo/index.php?thread='+$("#contentCode").attr('num'));
    }
});
