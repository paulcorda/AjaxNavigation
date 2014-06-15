(function($) {
$(document).ready(function() {

	function AjaxNavigation(el, url) {
		var loader = '<div id="ajaxnavigation_overlay"><div class="ajaxnavigation_loader"></div></div>';

		el.click(function() {
			$('body').css('cursor', 'wait');
			$('body').append(loader);

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
						$('.toolbar select').removeAttr('onchange');
					}
				},
				complete: function() {
					$('body').css('cursor', 'auto');
					$('#ajaxnavigation_overlay').remove();
				}
			});
		});
	};


	$(document).on('click', '.block-layered-nav a, .view-mode > a, .sort-by > a', function(e) {
		AjaxNavigation($(this), $(this).attr('href'));
		e.preventDefault();
	});


	$('.toolbar select').removeAttr('onchange');
	$(document).on('change', '.limiter > select, .sort-by > select', function(e) {
		AjaxNavigation($(this), $(this).val());
		e.preventDefault();
	});
});
})(jQuery);