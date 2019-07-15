$(function(){
    $(".countdown").each(function(){
        $(this).countdown($(this).attr("date"), function(event){
            $(this).text(event.strftime("%D days %H:%M:%S"));
        });
    });
});
