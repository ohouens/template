$(function(){
    if($("#contentWriter").length){
        $.get('?thread='+$("#contentWriter").attr('num')+'=&request=6', function(data){
            $("#contentWriter").html(data);
        });
    }
});
