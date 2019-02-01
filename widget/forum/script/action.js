$(function(){
    $('#banniere .alignement.square button').click(function(){
        document.location.href = '?thread='+$("#banniere .square").attr('id');
    });

    $('#subscribe').click(function(){
        if($(this).attr('valide') == 0){
            $.get('index.php?thread='+$(this).attr('num')+'&request=20', function(data){
                var dire="error";
                if(data == "0"){
                    $('#subscribe').text('unsubscribe').attr('class', 'reverse').attr('valide', '1');
                }else{
                    alert(dire);
                }
            });
        }else{
            $.get('index.php?thread='+$(this).attr('num')+'&request=21', function(data){
                var dire="error";
                if(data == "0"){
                    $('#subscribe').text('subscribe').attr('class', 'ButtonB').attr('valide', '0');
                }else{
                    alert(dire);
                }
            });
        }
    });
});
