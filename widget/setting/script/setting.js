$(function(){
    $.getScript('script/utils.js');

    $(".alignement.children button").click(function(){
        var parent = $(this).parent().parent().parent();
        parent.find('.a').css('display', 'none');
        parent.find('.b').css('display', 'flex');
    })

    $(".b p a").click(function(e){
        e.preventDefault();
        var parent = $(this).parent().parent();
        $(this).parent().css('display', 'none');
        parent.find(".grand.vide").css('display', 'block');
        $($(this).attr('href')).css('display', 'block');
    });

    $(".b .grand.vide a").click(function(e){
        e.preventDefault();
        var parent = $(this).parent().parent();
        $(this).parent().find('form').css('display', 'none');
        $(this).parent().css('display', 'none');
        parent.find("p").css('display', 'block');
    });
});
