$(function(){
    $('#login').on('submit', function(e){
		e.preventDefault();
        $('#erreurLog').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "erreur";
		$.post("index.php",$("#login").serialize()).done(function(data){
			if(data == "0"){
				location.reload();
			}else{
				if(data == "294")dire = "incorrect password";
				if(data == "293")dire = "this account doesn't exist";
				$('#erreurLog').text(dire);
				setTimeout(function(){
					$('#erreurLog').text("");
				},3000);
			}
		});
	});

	$('#signin').on('submit', function(e){
		e.preventDefault();
        $('#erreurSign').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "error";
		$.post("index.php",$("#signin").serialize()).done(function(data){
			if(data == "0"){
				location.reload();
			}else{
                if(data == "20")dire = "Pseudo must be length from 1 to 20 characters";
				if(data == "211")dire = "Pseudo must contain at least one lower case";
				if(data == "212")dire = "Pseudo can only contain lower case, underscore and numbers";
				if(data == "22")dire = "incorrect email";
				if(data == "23")dire = "Password must be at least 8 characters";
				if(data == "24")dire = "Password must at least contain 1 lower case";
				if(data == "25")dire = "Password must at least contain 1 upper case";
				if(data == "26")dire = "Password must at least contain 1 number";
                if(data == "291")dire = "this pseudo is already taken";
                if(data == "292")dire = "this email is already taken";
				$('#erreurSign').text(dire);
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
			$('#erreurRecup').text(data);
			setTimeout(function(){
				$('#erreurRecup').text("");
			},3000);
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
