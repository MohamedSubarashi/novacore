/**
 * NovaCore Customizer Preview
 *
 * @package NovaCore
 * @since 1.0.0
 */

'use strict';

(function ($) {
	// Dark mode live preview
	wp.customize('novacore_dark_mode', function (value) {
		value.bind(function (mode) {
			const body = document.body;
			body.classList.remove('dark-mode', 'dark-mode-system');

			if (mode === 'dark') {
				body.classList.add('dark-mode');
			} else if (mode === 'system') {
				body.classList.add('dark-mode-system');
			}
		});
	});

	// Container width
	wp.customize('novacore_container_width', function (value) {
		value.bind(function (width) {
			document.documentElement.style.setProperty('--nc-container-width', width + 'px');
		});
	});

	// Site layout
	wp.customize('novacore_site_layout', function (value) {
		value.bind(function (layout) {
			const body = document.body;
			body.classList.remove('site-layout-wide', 'site-layout-boxed', 'site-layout-framed');
			body.classList.add('site-layout-' + layout);
		});
	});
})(jQuery);
