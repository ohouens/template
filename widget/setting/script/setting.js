$(function(){
    $.getScript('script/utils.js');
    // previewImage(".profilePicture", "#changePdp");

    $(".b .grand.vide form").submit(function(e){
        e.preventDefault();
        $('#erreurSetting').html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
		var dire = "erreur";
        var $formulaire = $(this);
        var formdata = (window.FormData) ? new FormData($formulaire[0]) : null;
        var data = (formdata !== null) ? formdata : $formulaire.serialize();
        $.ajax({
            url: $formulaire.attr('action'),
            type: $formulaire.attr('method'),
            contentType: false,
            processData: false,
            data: data,
            success: function(data){
                if(data == "0")
    				$('#erreurSetting').html('<img src="style/icon/success.png" alt="success" class="wait" />');
    			else
    				$('#erreurSetting').text(traduction(data));
                setTimeout(function(){
                    $('#erreurSetting').text("");
                },3000);
            },error: function(){
                alert('erreur');
            }
        });
    });

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

    $(".profilePicture").click(function(){
        $('#changePdp input[type="file"]').trigger('click');
        $('#changePdp input[type="file"]').change(function(){
            readURL(this, "#changePdp img");
        });
    });
});
