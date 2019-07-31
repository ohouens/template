$(function(){
    $("#logPaypal").click(function(){
        $.get("index.php?store=purchase&object=licence", function(data){
			if(data.charAt(0) == "h" && data.charAt(1) == "t")
				document.location.href = data;
			else alert('An error occured');
        });
    });
});
