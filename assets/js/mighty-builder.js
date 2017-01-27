(function($) {
	$('.ct-counter').counterUp({
		delay: 10,
		time: 1000
	});


	// $('.mb-tabs').each(function () {
	// 	$(this).find(' > .nav > li:first > a').tab('show');
	// });

	var mb_container_reset = function (container) {
		container.each(function () {
			$(this).css({
				'margin-left': '0',
				'width': 'auto',
			});
		});
	};

	var mb_force_fullwidth = function () {
		var container = $('.mb-force-fullwidth');

		mb_container_reset(container);

		container.each(function () {
			var width = $(this).width(),
				fullWidth = $(document).width(),
				padding = (fullWidth - width) / 2;

			$(this).css({
				'margin-left': '-'+$(this).offset().left + 'px',
				'width': fullWidth + 'px',
			});
		});

	};

	mb_force_fullwidth();

	$(window).on('resize', mb_force_fullwidth);
	
})(jQuery);