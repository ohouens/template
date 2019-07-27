$(function(){
    $("#nonMail").submit(function(e){
        e.preventDefault();
        $.get("index.php?"+$(this).serialize(), function(data){
            if(data == "0" || data == "1"){
                window.location.replace('index.php?done=0');
            }else{
                $('#erreur').text(traduction(data));
                setTimeout(function(){
                    $('#erreur').text("");
                },3000);
            }
        });
    });
});
