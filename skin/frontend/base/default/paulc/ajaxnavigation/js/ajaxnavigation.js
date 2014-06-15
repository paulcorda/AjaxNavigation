(function($) {
	function AjaxLoading(option) {
		var loader = '<div id="paulc_ajaxnavigation_overlay"><div class="paulc_ajaxnavigation_loader"></div></div>';

		if(option == 'show') {
			$('body').addClass('wait')
					 .append(loader);
		}
		else {
			$('body').removeClass('wait');
			$('#paulc_ajaxnavigation_overlay').remove();
		}
	}

	function AjaxNavigation(url) {
			AjaxLoading('show');

			$.ajax({
				url: url,
				dataType: 'json',
				type: 'POST',
				data: { isAjax: 1 },
				success: function(data) {
					if(data.left) {
						$('.block-layered-nav').replaceWith(data.left);
					}

					if(data.list) {
						$('.category-products').replaceWith(data.list);
						$('.limiter > select, .sort-by > select').removeAttr('onchange');
					}
				},
				complete: function() {
					AjaxLoading('hide');
				}
			});
	};

	$(document).ready(function() {
		$(document).on('click', '.block-layered-nav a, .view-mode > a, .sort-by > a', function(e) {
			AjaxNavigation($(this).attr('href'));
			e.preventDefault();
		});

		$('.limiter > select, .sort-by > select').removeAttr('onchange');
		$(document).on('change', '.limiter > select, .sort-by > select', function(e) {
			$(this).removeAttr('onchange');
			AjaxNavigation($(this).val());
		});
	});
})(jQuery);