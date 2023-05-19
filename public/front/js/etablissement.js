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
    $('input[type=number]').on('keypress', function(e){
        return numberOnly(e)
    })
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

var changeAutreActivite = () => {
    $('#wrapper-autreActivite').hide();
    $('#etablissement_activiteAutre').on('change', function(){
        if($(this).is(":checked")){
            $('#wrapper-autreActivite').show();
        } else {
            $('#wrapper-autreActivite').hide();
        }
    })
    $('#etablissement_activiteAutre:checked').trigger('change');
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
    if(is_edit == false){
        /*$.ajax({
            async: false,
            type: 'get',
            url: app_etablissement_exist,
            data: {
                etablissement_nom :  $('#etablissement_nom').val()
            },
            dataType: 'json',
            success: function(response) {
                $('#etablissement_nom').removeClass('error');
                if(response.exist){
                    $('#etablissement_nom').parent().append('<span class="champ-erreur">Cet établissement est déjà inscrit. Si vous voulez gérer cet établissement, vous pouvez nous contacter</span>');
                    _error = true;
                } 
            }
        });*/
    }   
    $('.active input').each(function(e){
        if($(this).is(':required') && $(this).val() == ''){
            if($(this).is('#etablissement_telephone')){
                $(".active #etablissement_telephone").parents('.form-control').append('<span class="champ-erreur">Ce champ est obligatoire</span>');
            } else {
                $(this).parent().append('<span class="champ-erreur">Ce champ est obligatoire</span>');
            }
            _error = true;
        } 
    })

    if($('.active #etablissement_licenceC').length > 0){
        if(!$('.active #etablissement_licenceC').is(':checked') && !$('.active #etablissement_licenceB').is(':checked')
        && !$('.active #etablissement_licenceA').is(':checked')){
            $('.active #etablissement_licenceC').parent().append('<span class="champ-erreur">Vous devez sélectionner au moins une licence pour continuer</span>');
            _error = true;
        }
    }

    if($('.active .required-activites').length > 0) {
        var isckecked = false;
        $('.active .required-activites').each(function () {
            if($(this).is(':checked')){
                isckecked = true;
            }
        })

        if(!isckecked){
            _error = true;
            $('#wrapper-activites').append('<span class="champ-erreur">Vous devez sélectionner au moins une activité pour continuer</span>');
        }
    }

    if($('.active .required-activites').length == 0) {
        if($('.active .cb-activite').length > 0) {
            var isckecked = false;
            $('.active .cb-activite').each(function () {
                if($(this).is(':checked')){
                    isckecked = true;
                }
            })

            if(!isckecked){
                _error = true;
                $('#wrapper-activites').append('<span class="champ-erreur">Vous devez sélectionner au moins une activité pour continuer</span>');
            }
        }
    }

    if($('.active .rd-classement').length > 0) {
        var isckecked = false;
        $('.active .rd-classement').each(function () {
            if($(this).is(':checked')){
                isckecked = true;
            }
        })

        if(!isckecked){
            _error = true;
            $('#wrapper-classements').append('<span class="champ-erreur">Vous devez sélectionner au moins un classement pour continuer</span>');
        }
    }

    if($('.active #etablissement_dateOuverture').length > 0 && $('.active #etablissement_dateOuverture').val() == ''){
        $('.active #etablissement_dateOuverture').parent().append('<span class="champ-erreur">Ce champ est obligatoire</span>');
        _error = true;
    }
    
    $('.active input[type="checkbox"]').each(function(e){
        if($(this).is(':required') && !$(this).is(':checked')){
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
    var regexNif = /\d{10}$/;
    var regexStat = /\d{17}$/;
    var regexPhone = /^\d{2}\s?\d{2}\s?\d{3}\s?\d{2}$/;
    var regexCharaterOnly = /[^\d]/;
    var regexSiteWeb = /^((?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,}(?:\/[\w\-\.]*)*\/?)$/
    var regexFacebook = /(?:https?:\/\/)?(?:www\.)?(mbasic.facebook|m\.facebook|facebook|fb)\.(com|me)\/(?:(?:\w\.)*#!\/)?(?:pages\/)?(?:[\w\-\.]*\/)*([\w\-\.]*)/i
    var regexLinkedin = /^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile|company)\/([-a-zA-Z0-9]+)\/*/i
    var regexPieceJustification = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
    if($(".active #etablissement_telephone").length > 0){
        var phoneValid = regexPhone.test($(".active #etablissement_telephone").val());
        if(!phoneValid && $(".active #etablissement_telephone").val() != ''){
            $(".active #etablissement_telephone").parents('.form-control').append('<span class="champ-erreur">Téléphone invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_pieceJustificationFile").length > 0 && $(".active #etablissement_pieceJustificationFile").prop('files').length > 0){
        var file = $(".active #etablissement_pieceJustificationFile").prop('files')[0];
        if (!regexPieceJustification.exec(file.name)) {
            $(".active #etablissement_pieceJustificationFile").next().append('<span class="champ-erreur">Veuillez uploader un fichier valide (jpeg, png, pdf)</span>');
            _error = true;
        }
    }


    if($(".active #etablissement_siteWeb").length > 0){
        var siteWebValid = regexSiteWeb.test($(".active #etablissement_siteWeb").val());
        if(!siteWebValid && $("#etablissement_siteWeb").val() != ''){
            $(".active #etablissement_siteWeb").parent().append('<span class="champ-erreur">Site Web invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_email").length > 0){
        var emailValid = regexMail.test($(".active #etablissement_email").val());
        if(!emailValid && $("#etablissement_email").val() != ''){
            $(".active #etablissement_email").parent().append('<span class="champ-erreur">Adresse email invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_linkedin").length > 0){
        var linkedinValid = regexLinkedin.test($(".active #etablissement_linkedin").val());
        if(!linkedinValid && $("#etablissement_linkedin").val() != ''){
            $(".active #etablissement_linkedin").parent().append('<span class="champ-erreur">url invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_facebook").length > 0){
        var facebookValid = regexFacebook.test($(".active #etablissement_facebook").val());
        if(!facebookValid && $("#etablissement_facebook").val() != ''){
            $(".active #etablissement_facebook").parent().append('<span class="champ-erreur">url invalide</span>');
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
        var nif = $(".active #etablissement_nif").val();
        var nifValid = regexNif.test(nif);
        if((!nifValid && nif != '') || nif.length != 10){
            $(".active #etablissement_nif").parent().append('<span class="champ-erreur">La valeur est invalide</span>');
            _error = true;
        }
    }

    if($(".active #etablissement_stat").length > 0){
        var stat = $("#etablissement_stat").val();
        var statValid = regexStat.test(stat);
        if((!statValid && stat != '') || stat.length != 17){
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

var changeLicenceA = () => {
    if($('#etablissement_licenceA').length > 0) {
        $('#etablissement_licenceA').change(function() {
            if($(this).is(':checked')){
                $('#etablissement_dateLicenceA').attr('required', 'required')
                $('#etablissement_referenceA').attr('required', 'required')
            } else {
                $('#etablissement_dateLicenceA').removeAttr('required')
                $('#etablissement_referenceA').removeAttr('required')
            }
        });
    }
}
var changeLicenceB = () => {
    if($('#etablissement_licenceB').length > 0) {
        $("[id^='etablissement_activites_']").parent('label').hide();
        $('#etablissement_licenceB').change(function() {
            if(!$(this).is(':checked')){
                $('#etablissement_dateLicenceB').removeAttr('required')
                $('#etablissement_referenceB').removeAttr('required')
                $.ajax({
                    type: 'get',
                    url: app_category_licence_b,
                    data: {
                        category_id :  $('#etablissement_category').val()
                    },
                    dataType: 'json',
                    dataType: 'json',
                    success: function (response) {
                        $.each(response.data, function(index, value) {
                            $('#etablissement_activites_' + value).parent('label').remove();
                        });
                    }
                })
            } else {
                $('#etablissement_dateLicenceB').attr('required', 'required')
                $('#etablissement_referenceB').attr('required', 'required')
                $.ajax({
                    type: 'get',
                    url: app_category_licence_b,
                    data: {
                        category_id :  $('#etablissement_category').val(),
                        etablissement_id : $('#etablissement-id').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $.each(response.data, function(index, value) {
                            $('#etablissement_activites_' + value).parent('label').remove();
                        });
                        $('#wrapper-activites #item-activitesB').append(response.html);
                    }
                })
            }
            
        });
        $('#etablissement_licenceB:checked').trigger('change')
    }
}

var changeLicenceC = () => {
    if($('#etablissement_licenceC').length > 0) {
        $("[id^='etablissement_activites_']").parent('label').hide();
        $('#etablissement_licenceC').change(function() {
            if(!$(this).is(':checked')){
                $('#etablissement_dateLicenceC').removeAttr('required');
                $('#etablissement_referenceC').removeAttr('required');
                $.ajax({
                    type: 'get',
                    url: app_category_licence_c,
                    data: {
                        category_id :  $('#etablissement_category').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $.each(response.data, function(index, value) {
                            $('#etablissement_activites_' + value).parent('label').remove();
                        });
                    }
                })
            } else {
                $('#etablissement_dateLicenceC').attr('required', 'required')
                $('#etablissement_referenceC').attr('required', 'required')
                $.ajax({
                    type: 'get',
                    url: app_category_licence_c,
                    data: {
                        category_id :  $('#etablissement_category').val(),
                        etablissement_id : $('#etablissement-id').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $.each(response.data, function(index, value) {
                            $('#etablissement_activites_' + value).parent('label').remove();
                        });
                        $('#wrapper-activites #item-activitesC').append(response.html);
                    }
                })
            }
        });
        $('#etablissement_licenceC:checked').trigger('change');
    }
}

var changeActivite = () => {
    if($("[id^='etablissement_activites_']").length > 0) {
        $("[id^='etablissement_activites_']").on('change', function() {
            var that = $(this);
            if(that.is(':checked')){
                $.ajax({
                    type: 'get',
                    url: app_sous_activites,
                    data: {
                        activite_id :  that.val(),
                        etablissement_id : $('#etablissement-id').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        that.next().after(response.html);
                    }
                })
            } else {
                $('#wrapper-sous-activites').remove();
            }
        })
        $("[id^='etablissement_activites_']").trigger('change')
    }
}

var changeAvatar = () => {
    $('.replace-img').click(function(){
        $('#avatar').trigger('click')
    })

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
}

var changeAutreGroupement = () => {

    $('#etablissement_groupementAutre').on('change', function(){
        if($(this).is(":checked")){
            $('#wrapper-autregroupement').show();
        } else {
            $('#wrapper-autregroupement').hide();
        }
    })
    $('#etablissement_groupementAutre:checked').trigger('change');
}

var initFunction = () => {
    changeRegion();
    keypressNumberOnly();
    changeLicenceA();
    changeLicenceC();
    changeLicenceB();
    //changeActivite();
    changeMember();
    changeLicences();
    changeCategory();
    changeAutreActivite();
    changeAutreGroupement();
    changeAvatar();
    $( ".datepicker" ).datepicker({
        maxDate: -1,
        dateFormat: "dd/mm/yy",
        dayNamesMin: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
        monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
        monthNamesShort: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
        nextText: "Suivant",
        prevText: "Précédent"
    });
    $('.select-category').on('change', function() {
        var checked = $(this).prop('checked');
        if (checked) {
            $('.select-category').not(this).prop('checked', false);
            $('.select-category').not(this).trigger('change');
        }
    });
    
}

var hiddenCategory = (step) => {
    if(step != 1){
        $('.categories').hide();
    } else {
        $('.categories').show();
    }
}

$(document).ready(function () {
    
    initFunction();

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
        
        if($('#etablissement_licenceA').length > 0 && (!$('#etablissement_licenceB').is(':checked') && 
        !$('#etablissement_licenceC').is(':checked')) && $('#wrapper-coordonnees.active').length > 0) {
            $('.step.active').removeClass('active').nextAll('.step').eq(0).addClass('active');
            currentStep++;
        }

        if (currentStep < totalSteps) {
            $('.step.active').removeClass('active').nextAll('.step').eq(0).addClass('active');
            currentStep++;
            $('.prev').css('display', 'inline-block');
        }
        if (currentStep == totalSteps) {
            $('.next').hide();
            $('.btnadd').css('display', 'inline-block');
        }

        hiddenCategory(currentStep);
    });
    
    $('.prev').click(function() {
        if($('#etablissement_licenceA').length > 0 && (!$('#etablissement_licenceB').is(':checked') 
        && !$('#etablissement_licenceC').is(':checked'))  && $('#wrapper-infos_supplementaire.active').length > 0){
            $('.step.active').removeClass('active').prevAll('.step').eq(0).addClass('active');
            currentStep--;
        }
        
        if (currentStep > 1) {
            $('.step.active').removeClass('active').prevAll('.step').eq(0).addClass('active');
            currentStep--;
            $('.next').show();
            $('.btnadd').hide();
        }
        if (currentStep == 1) {
            $('.prev').hide();
        }

        hiddenCategory(currentStep);
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
                initFunction();
            }
        });
    });
});