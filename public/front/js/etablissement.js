var numberOnly =(e) => {
    var ASCIICode = (e.which) ? e.which : e.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}

var characterOnly =(e) => {
    var ASCIICode = (e.which) ? e.which : e.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return true;
    return false;
}

var keypressNumberOnly =() => {
    $('#etablissement_nif').on('keypress', function(e){
        return numberOnly(e)
    })

    $('#etablissement_stat').on('keypress', function(e){
        return numberOnly(e)
    })

    $('#etablissement_codePostal').on('keypress', function(e){
        return numberOnly(e)
    })

    $('#etablissement_proprietaire').on('keypress', function(e){
        return characterOnly(e)
    })

    $('#etablissement_gerant').on('keypress', function(e){
        return characterOnly(e)
    })
}

var changeLicences = () => {
    $('.licence .half').each(function(){
        let $inp = $(this).find("input[type=checkbox]");
        let self = $(this);
        if($inp.is(':checked')) {
            $(this).find('.obtention').removeClass('notselected'),
            $(this).find('.reference').removeClass('notselected'),
            $(this).find('.reference input').prop('disabled', false)
        }
        $inp.change(function() {
            if(this.checked) {
                self.find('.obtention').removeClass('notselected'),
                self.find('.reference').removeClass('notselected'),
                self.find('.reference input').prop('disabled', false)
                
            } else {
                self.find('.obtention').addClass('notselected'),
                self.find('.reference').addClass('notselected'),
                self.find('.reference input').prop('disabled', true)
            }
        });
    });
}

var changeRegion = () => {
    if($('#etablissement_region').length > 0) {
        $('#etablissement_region').on('change', function() {
            $.ajax({
                type: 'get',
                url: app_region_district,
                data: {
                    region_id :  $(this).val()
                },
                dataType: 'json',
                success: function (response) {
                    $('#etablissement_district').html(response.html);
                }
            })
        })
    }
}



var changeCategory = () => {
    if($('.select-category').length > 0) {
        $('.select-category').on('change', function() {
            value = $(this).val();
            isActive = $(this).is(':checked');
            if(value == 'GUIDE_REGIONAL' && isActive) {
                $('#wrapper-region').show();
                $('#etablissement_region').attr('required', 'required')
            } 
            if(value == 'GUIDE_REGIONAL' && !isActive){
                $('#wrapper-region').hide();
                $('#etablissement_region').removeAttr('required')
            }

            if(value == 'GUIDE_LOCAL' && isActive) {
                $('#wrapper-zone').show();
                $('#etablissement_zoneIntervention').attr('required', 'required')
            } 
            if(value == 'GUIDE_LOCAL' && !isActive){
                $('#wrapper-zone').hide();
                $('#etablissement_zoneIntervention').removeAttr('required')
            }

            if(value == 'GUIDE_SPECIALISE' && isActive) {
                $('#wrapper-preciser').show();
                $('#etablissement_categorieAutorisation').attr('required', 'required')
            } 
            if(value == 'GUIDE_SPECIALISE' && !isActive){
                $('#wrapper-preciser').hide();
                $('#etablissement_categorieAutorisation').removeAttr('required')
            }
        })

        $('.select-category:checked').trigger('change')
    }
}

var changeMember = () => {
    $('#wrapper-member input').change(function() {
        if (this.value == 1) {
            $('.slidergrpm').slideDown();
        } else {
            $('.slidergrpm').slideUp();
        }
    });
    setTimeout(() => $('#wrapper-member input:checked').trigger('change'), 500);
}

var hasError = () => {
    var _error = false;

    $('.active input').each(function(e){
        if($(this).is(':required') && $(this).val() == ''){
            $(this).parent().append('<span class="champ-erreur">Ce champ est obligatoire</span>');
            _error = true;
        } 
    })

    $('.active select').each(function(e){
        if($(this).is(':required') && $(this).val() == ''){
            $(this).parent().append('<span class="champ-erreur">Ce champ est obligatoire</span>');
            _error = true;
        } 
    })

    var regexMail = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var regexNumberOnly = /\d/;
    var regexPhone = /^\+261\s?\d{2}\s?\d{2}\s?\d{3}\s?\d{2}$/;
    var regexCharaterOnly = /[^\d]/;
    var regexSiteWeb = /^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/

    if($(".active #etablissement_telephone").length > 0){
        var emailValid = regexPhone.test($(".active #etablissement_telephone").val());
        if(!emailValid){
            $(".active #etablissement_telephone").parent().append('<span class="champ-erreur">Téléphone invalid (+261XX XX XXX XX)</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_siteWeb").length > 0){
        var siteWebValid = regexSiteWeb.test($(".active #etablissement_siteWeb").val());
        if(!siteWebValid && $("#etablissement_siteWeb").val() != ''){
            $(".active #etablissement_siteWeb").parent().append('<span class="champ-erreur">Site Web invalid</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_email").length > 0){
        var emailValid = regexMail.test($(".active #etablissement_email").val());
        if(!emailValid && $("#etablissement_email").val() != ''){
            $(".active #etablissement_email").parent().append('<span class="champ-erreur">Adresse email invalid</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_codePostal").length > 0){
        var codePostalValid = regexNumberOnly.test($(".active #etablissement_codePostal").val());
        if(!codePostalValid && $("#etablissement_codePostal").val() != ''){
            $(".active #etablissement_codePostal").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_nif").length > 0){
        var nifValid = regexNumberOnly.test($(".active #etablissement_nif").val());
        if(!nifValid && $("#etablissement_nif").val() != ''){
            $(".active #etablissement_nif").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_stat").length > 0){
        var statValid = regexNumberOnly.test($("#etablissement_stat").val());
        if(!nifValid && $("#etablissement_stat").val() != ''){
            $(".active #etablissement_stat").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }
    
    if($(".active #etablissement_proprietaire").length > 0){
        var proprietaireValid = regexCharaterOnly.test($("#etablissement_proprietaire").val());
        if(!proprietaireValid && $("#etablissement_proprietaire").val() != ''){
            $(".active #etablissement_proprietaire").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_gerant").length > 0){
        var gerantValid = regexCharaterOnly.test($("#etablissement_gerant").val());
        if(!gerantValid && $("#etablissement_gerant").val() != ''){
            $(".active #etablissement_gerant").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }

    return _error;
}

$(document).ready(function () {
    changeRegion();
    keypressNumberOnly();
    var currentStep = 1;
    var totalSteps = $('.step').length;

    $('input[type="file"]').on('change', function() {
        var regexAvatar = /(\.jpg|\.jpeg|\.png|\.svg)$/i;
        const file = this.files[0];
    
        if (!regexAvatar.exec(file.name)) {
            $(".logo-entreprise").next().append('<span class="champ-erreur">Veuillez uploader un fichier valide (jpeg, png, svg)</span>');
            return false;
        }
    });

    if (is_edit) $('.step:first').addClass('active');
    
    $('.next').click(function() {
        $('.active .champ-erreur').remove();

        if(hasError()) return false;

        if (currentStep < totalSteps) {
            $('.step.active').removeClass('active').nextAll('.step').eq(0).addClass('active');
            currentStep++;
            $('.prev').css('display', 'inline-block');
        }
        if (currentStep == totalSteps) {
            $('.next').hide();
            $('.btnadd').css('display', 'inline-block');
        }
    });
    
    $('.prev').click(function() {
        if (currentStep > 1) {
            $('.step.active').removeClass('active').prevAll('.step').eq(0).addClass('active');
            currentStep--;
            $('.next').show();
            $('.btnadd').hide();
        }
        if (currentStep == 1) {
            $('.prev').hide();
        }
    });


    $('#frm-etablissement').on('submit',function(){
        $('.active .champ-erreur').remove();
        
        if(hasError()) return false;

        return true;
    })

    $('.categories select').on('change', function() {
        currentStep = 1;
        $('.step').removeClass('active')
            
        $('.prev').hide();
        $('.btnadd').hide();
        $('.next').show();
        data = {};
        data[$(this).attr('name')] = $(this).val();
        $.ajax({
            type: 'POST',
            url: app_front_etablissement_add,
            data: data,
            dataType: 'html',
            success: function (response) {
                totalSteps = $(response).find('.step').length;
                $('#wrapper-field').replaceWith(
                    $(response).find('#wrapper-field')
                );

                $('.step:first').addClass('active');

                $('#avatar').change(function () {
                    fileSize = this.files[0].size;
                    var MAX_FILE_SIZE = 2 * 1024 * 1024;
                    if (fileSize > MAX_FILE_SIZE) {
                        alert('Photo de profil trop large, taille max 2M')
                    } else {
                        var image = document.getElementById('avatar-img');
                        image.src = URL.createObjectURL(event.target.files[0]);
                    }
                })

                changeLicences();
                keypressNumberOnly();
                changeRegion();
                changeCategory();
                changeMember()
            }
        });
    });
    changeMember();
    changeLicences();
    changeCategory();
});