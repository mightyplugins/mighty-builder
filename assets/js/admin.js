(function ($) {
	"use strict";

	$('.mb-about-wrap .nav-tab-wrapper > a').on('click', function (e) {
		e.preventDefault();
		var panelId = $(this).attr('href');

		$('.mb-settings-panel.mb-panel-active').removeClass('mb-panel-active');
		$('.nav-tab-wrapper > a.nav-tab-active').removeClass('nav-tab-active');

		$(this).addClass('nav-tab-active');
		$(panelId).addClass('mb-panel-active');
	});
})(jQuery);