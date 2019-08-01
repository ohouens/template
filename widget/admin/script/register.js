$(function(){
    $.getScript('script/utils.js');

    $('#login').on('submit', function(e){
		e.preventDefault();
        $('#erreurLog').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		$.post("index.php",$("#login").serialize()).done(function(data){
			if(data == "0"){
				location.reload();
			}else{
				$('#erreurLog').text(traduction(data));
				setTimeout(function(){
					$('#erreurLog').text("");
				},3000);
			}
		});
	});

	$('#signin').on('submit', function(e){
		e.preventDefault();
        $('#erreurSign').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		$.post("index.php",$("#signin").serialize()).done(function(data){
			if(data == "0"){
				location.reload();
			}else{
				$('#erreurSign').text(traduction(data));
				setTimeout(function(){
					$('#erreurSign').text("");
				},3000);
			}
		});
	});

	$('#recup').on('submit', function(e){
		e.preventDefault();
        $('#erreurRecup').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		$.post("index.php",$("#recup").serialize()).done(function(data){
            if(data == "0"){
                $('#erreurRecup').html('<img src="style/icon/success.png" alt="success" class="wait" />');
    			setTimeout(function(){
    				$('#erreurRecup').text("You can check your email");
    			},1500);
            }else{
    			$('#erreurRecup').text(traduction(data));
    			setTimeout(function(){
    				$('#erreurRecup').text("");
    			},3000);
            }
		});
	});

	$('#changeMdp').on('submit', function(e){
		e.preventDefault();
		$.post("index.php",$(this).serialize()).done(function(data){
			$('#erreurPasse').text(data);
			setTimeout(function(){
				$('#erreurPasse').text("");
			},3000);
		});
	});


	$('#helpSign').on('click', function(e){
		e.preventDefault();
		$('#login').css('display', 'none');
		$('#signin').css('display', 'block');
	});

	$('#helpPass').on('click', function(e){
		e.preventDefault();
		$('#login').css('display', 'none');
		$('#recup').css('display', 'block');
	});

	$('#helpLog').on('click', function(e){
		e.preventDefault();
			$('#signin').css('display', 'none');
		$('#login').css('display', 'block');
	});

	$('#helpLogBis').on('click', function(e){
		e.preventDefault();
		$('#recup').css('display', 'none');
		$('#login').css('display', 'block');
	});
});
