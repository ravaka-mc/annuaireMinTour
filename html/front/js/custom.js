$(document).ready(function() {
	function checkScroll()
	{
		var posScroll = $(document).scrollTop();

		if(posScroll>0) 
			$('body').addClass('scroll')
		else
			$('body').removeClass('scroll')
	}
    $(window).scroll(function(){
		checkScroll();
	});
	$(window).resize(function(){
		checkScroll();
	});
	checkScroll();

	"use strict";
  $(".hamburger").click(function() {
		$(this).toggleClass("active");
		$('nav').toggleClass("active");
		$('#headermenu').slideToggle();
		$('body').toggleClass('menu-open');
	});
	$('.close-mobile-menu').click(function(){
		$(".hamburger").removeClass("active");
		$('nav').removeClass("active");
		$('#headermenu').slideUp('fast');
		$('body').removeClass('menu-open');
	})

	$(function() {
		$('.tabsnav a').click(function() {
		  $('.tabsnav li').removeClass('active');
		  $(this).parent().addClass('active');
		  let currentTab = $(this).attr('href');
		  $('.tabscontent > div').hide();
		  $(currentTab).show();
		  return false;
		});
	  });	


	// menu mobile + custom mobile //
	$("#headermenu li i").click(function() {
		$(this).toggleClass('active');
		$(this).next().slideToggle();
	});	
	  
	// scroll to top - bottom //
	$(".btn-scroll").click(function() {
		var c = $(this).attr("href");
		$('html, body').animate({ scrollTop: $(c).offset().top }, 1000, "swing");
		return false;
	});
});
