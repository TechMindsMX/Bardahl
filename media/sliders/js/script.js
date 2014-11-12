/**
 * Main JavaScript file
 *
 * @package         Sliders
 * @version         3.5.7
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

(function($) {
	$(document).ready(function() {
		if (typeof( window['nn_sliders_use_hash'] ) != "undefined") {
			nnSliders = {
				show  : function(id, scroll, ignoreparents) {
					var openparents = 0;
					var $el = $('#' + id);
					if (!ignoreparents) {
						$el.closest('.nn_sliders').closest('.accordion-body').each(function(i, parent) {
							if (!$(parent).hasClass('in')) {
								nnSliders.show(parent.id, 0);
								openparents = 1;
							}
						});
					}
					if (openparents) {
						nnSliders.show(id, scroll, 1);
					} else {
						if (!$el.hasClass('in')) {
							$el.collapse({
								toggle: true,
								parent: $el.parent().parent()
							});
							if (!$el.hasClass('in')) {
								$el.collapse('toggle');
							}
						} else {
						}

						$el.focus();
					}
				},
			};

			$('.nn_sliders > .accordion-group > .accordion-body').on('show show.bs.collapse', function(e) {
				$(this).parent().addClass('active');
				e.stopPropagation();
			});
			$('.nn_sliders > .accordion-group > .accordion-body').on('hidden hidden.bs.collapse', function(e) {
				$(this).parent().removeClass('active');
				e.stopPropagation();
			});


			if (nn_sliders_use_hash) {
				if (window.location.hash) {
					var id = window.location.hash.replace('#', '');
					if (id.indexOf("&") == -1 && id.indexOf("=") == -1 && $('#' + decodeURIComponent(id) + '.accordion-body').length > 0) {
						if (!nn_sliders_urlscroll) {
							// scroll to top to prevent browser scrolling
							$('html,body').animate({scrollTop: 0});
						}
						nnSliders.show(id, nn_sliders_urlscroll);
					}
				}
				$('.nn_sliders > .accordion-group > .accordion-body').on('show show.bs.collapse', function(e) {
					// prevent scrolling on setting hash, so temp empty the id of the element
					var id = this.id
					this.id = '';
					window.location.hash = id;
					this.id = id;
					e.stopPropagation();
				});
			}

			if (window.location.hash) {
				/* Open parent tabs and scroll to named anchor links within tabs */
				var id = window.location.hash.replace('#', '');
				var $el = $('a[name="' + id + '"][data-toggle!="collapse"]');

				if ($el) {
					var hasparents = 0;
					$el.closest('.nn_sliders').closest('.accordion-body').each(function(i, parent) {
						if (!$(parent).hasClass('in')) {
							nnSliders.show(parent.id, 0);
							hasparents = 1;
						}
					});

					if (hasparents) {
						setTimeout(function() {
							$('html,body').animate({scrollTop: $el.offset().top});
						}, 500);
					}
				}
			}


			// Re-inintialize Google Maps on slider show
			$('.nn_sliders > .accordion-group > .accordion-body.in iframe').each(function() {
				$(this).attr('reloaded', true);
			});
			$('.nn_sliders > .accordion-group > .accordion-body').on('show show.bs.collapse', function($e) {
				if (typeof initialize == 'function') {
					initialize();
				}
				$(this).find('iframe').each(function() {
					if (!$(this).attr('reloaded')) {
						this.src += '';
						$(this).attr('reloaded', true);
					}
				});
			});
			$(window).resize(function() {
				if (typeof initialize == 'function') {
					initialize();
				}
				$('.nn_sliders > .accordion-group > .accordion-body iframe').each(function() {
					$(this).attr('reloaded', false);
				});
				$('.nn_sliders > .accordion-group > .accordion-body.in iframe').each(function() {
					this.src += '';
					$(this).attr('reloaded', true);
				});
			});
		}
	});
})(jQuery);

/* For custom use */
function openAllSliders() {
	(function($) {
		$('.nn_sliders').each(function(e, el) {
			id = $(el).find('.accordion-body').first().attr('id');
			nnSliders.show(id);
		});
	})(jQuery);
}

function closeAllSliders() {
	(function($) {
		$('.nn_sliders').each(function(e, el) {
			id = $(el).find('.accordion-body').first().attr('id');
			var $el = $('#' + id);
			$el.collapse('hide');
		});
	})(jQuery);
}
