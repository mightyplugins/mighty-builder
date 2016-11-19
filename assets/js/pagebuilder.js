(function($) {
	$('.ct-counter').counterUp({
		delay: 10,
		time: 1000
	});


	$('.ct-tabs').each(function () {
		$(this).find(' > .nav > li:first > a').tab('show')
	});
	// $('.ct-tabs > .nav > li:first > a').tab('show')
})(jQuery);