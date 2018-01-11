
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
	$('.r-form-1').waypoint(function() {
		$('nav').toggleClass('navbar-no-bg');
	});
	
    /*
        Background slideshow
    */
    $('.r-form-1-container').backstretch("lcss/assets/img/backgrounds/1.jpg");
    $('.r-form-5-container').backstretch("lcss/assets/img/backgrounds/2.jpg");
    $('.r-form-8-container').backstretch("lcss/assets/img/backgrounds/3.jpg");
    $('.r-form-10-container').backstretch("lcss/assets/img/backgrounds/4.jpg");
    
    /*
        Wow
    */
    new WOW().init();
	
});
