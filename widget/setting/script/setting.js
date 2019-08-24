$(function(){
    var save = "";
    $.getScript('script/utils.js');
    swapGender()

    $(".b .grand.vide form").submit(function(e){
        e.preventDefault();
        var displayError = $(this).parent().find('.displayError');
        displayError.html('<img src="style/icon/wait.gif" alt="wait.." class="wait" />');
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
    				displayError.html('<img src="style/icon/success.png" alt="success" class="wait" />');
    			else
    				displayError.text(traduction(data));
                setTimeout(function(){
                    displayError.text("");
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
        if($(this).attr("href").charAt(0) == "#"){
            e.preventDefault();
            var parent = $(this).parent().parent();
            $(this).parent().css('display', 'none');
            parent.find(".grand.vide").css('display', 'block');
            $($(this).attr('href')).css('display', 'block');
        }
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

    $(".gender").hover(function(){
        $(this).attr('src', 'media/dataProfiling/gender/'+$(this).attr('alt')+'.png');
    }, function(){
        $(this).attr('src', 'media/dataProfiling/gender/'+$(this).attr('origin')+'.png');
        swapGender();
    });
    $(".gender").click(function(){
        $(".gender").removeClass("selected");
        $(this).addClass("selected");
        swapGender();
        $("#changeGender input[name='gender']").val($(this).attr('val'));
    });

    $(".social").click(function(){
        $(".social").removeClass("selected");
        $(this).addClass("selected");
        $("#changeSocial input[name='social']").val($(this).attr('val'));
    });

    $(".hobby").click(function(){
        $(".hobby").removeClass("selected");
        $(this).addClass("selected");
        $("#changeHobby input[name='hobby']").val($(this).attr('val'));
    });

    $("#changeCountry select").change(function(){
        $("#changeCountry img").attr('src', 'https://www.countryflags.io/'+$(this).val()+'/flat/64.png').attr('alt', $(this).val());
    });

    function swapGender(){
        $(".gender").each(function(){
            $(this).attr('src', 'media/dataProfiling/gender/'+$(this).attr('origin')+'.png');
        });
        $(".gender.selected").each(function(){
            $(this).attr('src', 'media/dataProfiling/gender/'+$(this).attr('alt')+'.png');
        });
    }
});
