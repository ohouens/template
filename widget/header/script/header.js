$(function(){
    var option = "Profile";
    init();

    $(".wrapper, footer").click(function(){
        $('.plus').each(function(){
            if($(this).css("display") == "block")
    		      $(this).css("display", "none");
        });
    });

    $(".action").click(function(){
		if($(this).find(".plus").css("display") == "none")
			$(this).find(".plus").css("display", "block");
		else
			$(this).find(".plus").css("display", "none");
	});

    $("#menu .plus a").click(function(e){
        e.preventDefault();
        $("#action").find('#'+option).css("display", "none");
        option = $(this).attr('option');
        $('#'+option).css("display", "inline-block");
        $("#menu").css('background', $('#'+option).css('background-color'));
        screen();
    });

    function screen(){
        $.get('index.php?screen='+option, function(data){
            $("header .alignement.gauche").html(data);
        });
    }

    function init(){
        $('#action .action').not($('.action')[0]).css("display", "none");
        screen();
    }
});
