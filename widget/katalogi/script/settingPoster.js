$(function(){
    $.getScript('script/utils.js');
    $("#settingPoster #resume .dup").click(function(){
        if($("#addresses").css("display") == "none"){
            $("#availableSlots .deno").text(parseInt($("#availableSlots .deno").text())+1);
            $("#addresses").css("display", "inline");
            $("#settingPoster #resume .dup").css("visibility", "hidden");
            slotStatut();
        }
    });
    initDup();

    $("#adAdding").on('blur', function(){
        $('#erreurCreate').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		$.getJSON('index.php', {address: encodeURIComponent($('#adAdding').val()), katalogi: "getAddress"}).done(function(data){
			if(data.status == "OK"){
                $('#erreurCreate').text("");
				$('input[name="extraAddress"]').val(data.results[0].formatted_address+" || "+data.results[0].geometry.location.lat+" || "+data.results[0].geometry.location.lng);
                $('#adAdding').val(data.results[0].formatted_address);
			}else{
				$('#erreurCreate').text("incorrect adddress");
				setTimeout(function(){
					$('#erreurCreate').text("");
				},3000);
			}
		}).error(function(data){
			alert("error: "+data);
		});
	});

    $("#resume #savePoster").click(function(){
        $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
        $.post($("#resume form").attr('action'), $("#resume form").serialize()).done(function(data){
            if(data == "00000000000"){
                $('input[name="extraAddress"]').val("");
                $('#adAdding').val("");
                $("#addresses").css("display", "none");
                $("#settingPoster #resume .dup").css("visibility", "visible");
                $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
                initDup();
            }
            else
                $("#error").html('<img src="style/icon/fail.png" alt="fail" class="wait" />');
            setTimeout(function(){
                $("#error").text("");
            },3000);
            // $("#error").html(data);
            if(data.charAt(7) == 1 || data.charAt(8) == 1)
                alert("A licence is required to alert or notify.");
        });
    });

    function initDup(){
        $.get("index.php?thread="+$("#resume").attr('num')+"&request=57", function(data){
            $("#statutContainer").html(data);
        });
        $.get("index.php?thread="+$("#resume").attr('num')+"&request=58", function(data){
            $("#addDup").html(data);
            $(".addressDup .minus").click(function(){
                $.get("index.php?thread="+$("#resume").attr('num')+"&request=59&lat="+$(this).attr("lat")+"&long="+$(this).attr("long"), function(data){
                    if(data == "0")
                        initDup();
                });
            });
        });
    }
});
