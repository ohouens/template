$(function(){
    $.getScript('script/utils.js');

    $("#createPoster #submit").click(function(){
        $('#erreurCreate').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "erreur";
        var $formulaire = $("#createPoster form");
        var formdata = (window.FormData) ? new FormData($formulaire[0]) : null;
        var data = (formdata !== null) ? formdata : $formulaire.serialize();
        $.ajax({
            url: $formulaire.attr('action'),
            type: $formulaire.attr('method'),
            contentType: false,
            processData: false,
            data: data,
            success: function(data){
                if(data.length == 40){
    				window.location.replace('index.php?thread='+data);
    			}else{
    				$('#erreurCreate').text(traduction(data));
    				setTimeout(function(){
    					$('#erreurCreate').text("");
    				},3000);
    			}
            },error: function(){
                alert('erreur');
            }
        });
    });

    $("#createPoster form").submit(function(e){
        e.preventDefault();
        $('#createPoster #submit').trigger('click');
    });

    $('#createPoster form input[type="file"]').change(function(){
        readURLBis(this, ".wrapper");
    });

    $('#createPoster form select[name="subtype"]').change(function(){
        $('#createPoster form input[name="extra"]').addClass('vide');
        $('#createPoster form input[name="address"]').addClass('vide');
        switch ($(this).val()) {
            case "1":
                $('#createPoster form input[name="address"]').removeClass('vide');
                break;
            case "2":
                $('#createPoster form input[name="extra"]').removeClass('vide').attr("placeholder", "your code");
                break;
            case "3":
                $('#createPoster form input[name="extra"]').removeClass('vide').attr("placeholder", "https://yourwebsite.com/");
                break;
            default:
                break;
        }
    });

    function readURLBis(input, output) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(output).css('background-image', 'url("'+e.target.result+'")').css('background-size', 'cover');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
});
