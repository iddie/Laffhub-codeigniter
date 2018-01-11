
function scroll_to(clicked_link, nav_height) {
	var element_class = clicked_link.attr('href').replace('#', '.');
	var scroll_to = 0;
	if(element_class != '.top-content') {
		element_class += '-container';
		scroll_to = $(element_class).offset().top - nav_height;
	}
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 1000);
	}
}


jQuery(document).ready(function() {
	
	/*
	    Navigation
	*/
	$('a.scroll-link').on('click', function(e) {
		e.preventDefault();
		scroll_to($(this), $('nav').outerHeight());
	});
	// toggle "navbar-no-bg" class
	$('.l-form-1').waypoint(function() {
		$('nav').toggleClass('navbar-no-bg');
	});
	
    /*
        Background slideshow
    */
    $('.l-form-1-container').backstretch("assets/img/backgrounds/3.jpg");
    $('.l-form-3-container').backstretch("assets/img/backgrounds/1.jpg");
    $('.l-form-5-container').backstretch("assets/img/backgrounds/5.jpg");
    $('.l-form-8-container').backstretch("assets/img/backgrounds/2.jpg");
    $('.l-form-10-container').backstretch("assets/img/backgrounds/6.jpg");
    
    /*
        Wow
    */
    new WOW().init();
	
});
