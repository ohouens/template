$(function(){
    if($("#contentStatistic").length){
        $.get('index.php?origin=displayForum&getObject=statistic&wit', function(data){
            // alert(data);
        });
    }
});
