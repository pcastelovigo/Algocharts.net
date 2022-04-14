$(document).ready(function () {
	"use strict"; // start of use strict

	/*==============================
	Menu
	==============================*/
	$('.header__btn').on('click', function() {
		$(this).toggleClass('header__btn--active');
		$('.header__menu').toggleClass('header__menu--active');
	});

	$('.header__search .close, .header__action--search button').on('click', function() {
		$('.header__search').toggleClass('header__search--active');
	});

	/*==============================
	Multi level dropdowns
	==============================*/
	$('ul.dropdown-menu [data-toggle="dropdown"]').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();

		$(this).siblings().toggleClass('show');
	});

	$(document).on('click', function (e) {
		$('.dropdown-menu').removeClass('show');
	});


	/*==============================
	Section bg
	==============================*/
	$('.main__video-bg, .author__cover--bg, .main__author, .collection__cover, .hero__slide').each(function(){
		if ($(this).attr('data-bg')){
			$(this).css({
				'background': 'url(' + $(this).data('bg') + ')',
				'background-position': 'center center',
				'background-repeat': 'no-repeat',
				'background-size': 'cover'
			});
		}
	});


});

$("#dropdown").on("click", function(e){
//	e.preventDefault();
	
	if($(this).hasClass("open")) {
	  $(this).removeClass("open");
	  $(this).children("ul").slideUp("fast");
	} else {
	  $(this).addClass("open");
	  $(this).children("ul").slideDown("fast");
	}
  });
