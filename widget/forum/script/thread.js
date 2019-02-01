function readChat(num){
    $.get("index.php?thread="+num+"&request=0", function(data){
        $("#chatReading").html(data);
    });
}
//-----------------------------------------------------------------
if($("#chatReading").length){
    readChat($("#chatReading").attr("num"));
}
//-----------------------------------------------------------------
$("#chatWriting form").submit(function(e){
    e.preventDefault();
    var num = $("#chatWriting").attr('num');
    $.post("index.php?thread="+num+"&request=1", $(this).serialize()).done(function(data){
        if(data == "0"){
            $('#chatWriting form input[type="text"]').val("");
            readChat(num);
        }else{
            alert(data);
        }
    });
});
