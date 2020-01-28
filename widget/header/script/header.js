$(function(){
    var option = "Profile";
    let searchParams = new URLSearchParams(window.location.search).get('menu');
    if(searchParams != null)
        option = searchParams
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

    $(".wrapper").css('padding-top', $("header").height()+"px");

    function screen(){
        $.get('index.php?screen='+option, function(data){
            $("header .alignement.gauche").html(data);
        });
    }

    function init(){
        $('#action .action').not($('#'+option)).css("display", "none");
        $("#menu").css('background', $('#'+option).css('background-color'));
        screen();
    }
});
