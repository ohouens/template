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

    if($(".renewalCountdown").attr("date") == "infinity"){
        $(".renewalCountdown").text("∞");
    }else{
        $(".renewalCountdown").each(function(){
            $(this).countdown($(this).attr("date"), function(event){
                $(this).text(event.strftime("%D days %H:%M:%S"));
            });
        });
    }

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

    $("#resume #deletePoster").click(function(){
        if(confirm("Are you sure about this ? This action will be permanent")){
            $("#error").html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
            $.get("index.php?thread="+$("#resume").attr('num')+"&request=42", function(data){
                if(data == "0"){
                    $("#error").html('<img src="style/icon/success.png" alt="success" class="wait" />');
                    setTimeout(function(){
                        if($("#setting").length)
                            window.location.href = "index.php?katalogi=settings&menu=Katalogi";
                        else
                            window.location.href = "index.php?thread=none&settings";
                    },3000);
                }else{
                    $("#error").html('<img src="style/icon/fail.png" alt="fail" class="wait" />');
                    setTimeout(function(){
                        $("#error").text("");
                    },3000);
                }
            });
        }
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
            if(data == "000000000770"){
                if(confirm("You have no open slot to clone this poster. Would you unlock 1 slot ?")){
                    window.location.replace('index.php?store=detail&object=one&menu=Store');
                }
            }
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
