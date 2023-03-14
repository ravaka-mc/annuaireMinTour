var changeLicences = () => {
	$('.licence .half').each(function(){
		let $inp = $(this).find("input[type=checkbox]");
		let self = $(this);
		$inp.change(function() {
			if(this.checked) {
				self.find('.obtention').removeClass('notselected')
			} else {
				self.find('.obtention').addClass('notselected')
			}
		});
	});
}

$(document).ready(function() {
	$('<i></i>').insertBefore('.sub-menu');
	$('.has-child i').click(function(){
		$('.has-child i').not($(this)).removeClass('active').parent().find('.sub-menu').slideUp('fast');
		$(this).toggleClass('active');
		$(this).next().slideToggle();

	});

	$(".infoad").click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		$('.deco').fadeIn();
	});
	ww = $( window ).width();
	if(ww < 1024){
		$(document).click(function() {
			$('.deco').fadeOut();
		});
	}

	var i = 2;
	$("#add").click(function() {
		var last = $(".form-groupe div").last();
		var appended = $("<div><input type=\"text\" name=\"nombtn"+i+"\" placeholder=\"Ecrivez le nom du bouton\"><span class=\"removeinput\"></span></div>");
		last.after(appended);
		$(".removeinput").click(function() {
			$(this).parent().remove();
			$("#add").show();	
		});
		var n = $( ".form-groupe div" ).length;
		if (n >= 4){
			$(this).hide();
		}
	});
	$("#viewpass").click(function() {
		$(this).toggleClass("active");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
		  input.attr("type", "text");
		} else {
		  input.attr("type", "password");
		}
	});
	$.fn.DataTable.ext.pager.numbers_length = 4;
	$('#mc-table').DataTable( {searching: false,
		"dom": '<"toolbar">frtip',
		"pageLength": 1,
		"language": {
			"lengthMenu": "Afficher _MENU_ éléments",
			"zeroRecords": "Rien n'a été trouvé, désolé",
			"info": "Nombre de lignes <span class='current'>_PAGE_</span> sur <span>_PAGES_</span>",
			"infoEmpty": "Non disponible",
			"infoFiltered": "(filtré sur _MAX_ enregistrements)",
			"search": "",
			"sPaginationType": "3",
			"paginate": {
			  "previous": "<",
			  "next": ">"
			}
		}
	});
	$("#mc-table_paginate, #mc-table_info").addClass('addedParent');
	$('.addedParent').wrapAll("<div class='flex navigation' />");

	/*** Custom select ***/
	$('.custom-select').each(function(){
		$(this).find('.default').click(function(e){
			if( $(this).siblings('ul').is(":visible") ) {
				$(this).siblings('ul').slideUp();
			}
			else {
				$('.custom-select ul').slideUp();
				$(this).siblings('ul').slideDown();
				e.stopPropagation();
			}
		});
		$("body").click(function(e){
			$('.custom-select ul').slideUp();
		});
		$(this).find('li').click(function(){
			var $thistext = $(this).text();
			$(this).parents('.custom-select').find('.default').text($thistext);
			if($(this).is("[data-url]")) {
				$('.custom-select ul').slideUp();
				window.location.href = $(this).data('url');
			} else {
				$('.custom-select ul').slideUp();
				return false
			}
		});
	});
	
	

	setTimeout(() => $('#wrapper-member input:checked').trigger('change'), 500);
	$('#wrapper-member input').change(function() {
		if (this.value == 1) {
			$('.slidergrpm').slideDown();
		} else {
			$('.slidergrpm').slideUp();
		}
	});

	changeLicences();

	$('.deco a').click(function(){
		url = $(this).attr('href');
		window.location.href = url;
	})

	
});