$(function(){
    $("#licence #logPaypal").click(function(){
        $.get("index.php?store=purchase&object=licence", function(data){
			if(data.charAt(0) == "h" && data.charAt(1) == "t")
				document.location.href = data;
			else alert('An error occured');
        });
    });
    $("#beginner #logPaypal").click(function(){
        $.get("index.php?store=purchase&object=beginner", function(data){
			if(data.charAt(0) == "h" && data.charAt(1) == "t")
				document.location.href = data;
			else alert('An error occured');
        });
    });
    $("#advanced #logPaypal").click(function(){
        $.get("index.php?store=purchase&object=advanced", function(data){
			if(data.charAt(0) == "h" && data.charAt(1) == "t")
				document.location.href = data;
			else alert('An error occured');
        });
    });
    $("#one #logPaypal").click(function(){
        $.get("index.php?store=purchase&object=one", function(data){
			if(data.charAt(0) == "h" && data.charAt(1) == "t")
				document.location.href = data;
			else alert('An error occured');
        });
    });
});
