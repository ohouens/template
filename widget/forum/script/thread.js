function readChat(num){
    $.get("index.php?thread="+num+"&request=0", function(data){
        $("#chatReading").html(data);
    });
}
//-----------------------------------------------------------------
if($("#chatReading").length){
    readChat($("#chatReading").attr("num"));
}

if($("#subscribe").length){
    $.get('index.php?thread='+$("#subscribe").attr('num')+'&request=22', function(data){
        if(data == "0"){
            $('#subscribe').text('Unsubscribe').attr('class', 'reverse').attr('valide', '1');
        }else{
            $('#subscribe').text('Subscribe').attr('class', 'ButtonB').attr('valide', '0');
        }
    });
}

if($("#action_current").length){
    $.get('index.php?thread='+$("#action_container").attr('num')+'&request=24', function(data){
        if(data == ""){
            $("#action_current").html("<strong>False</strong>");
        }else{
            $("#action_current .center").html(data);
            $("#action_current .center").first().find('.choices .choice').click(function(){
                    alert($(this).text());
            });
        }
    });
}
//-----------------------------------------------------------------
$("#chatWriting form").submit(function(e){
    // e.preventDefault();
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
