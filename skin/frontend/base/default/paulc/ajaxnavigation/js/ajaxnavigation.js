(function($) { $(document).ready(function() {

	// Function Prototype
	function AjaxNavigation(el, url) 
	{
		var loader = '<div id="ajaxnavigation_loader"></div>';

		el.click(function() {
			$('body').css('cursor', 'wait');
			$('body').append(loader);

			$.ajax({
				url: url,
				dataType: 'json',
				type: 'POST',
				data: {isAjax: 1},
				success: function(data) {
					if(data.view) {
						$('.col-left').html(data.view);
					}

					if(data.list) {
						$('.category-products').html(data.list);
						$('.toolbar select').removeAttr('onchange');
					}
				},
				complete: function() {
					$('body').css('cursor', 'auto');
					$('#ajaxnavigation_loader').remove();
				}
			});
		});
	}
	
	// TEST
	// Function call
	$('body').on('click', '.block-layered-nav a, .view-mode > a, .sort-by > a', function(e) {
		AjaxNavigation($(this), $(this).attr('href'));
		
		e.preventDefault();
	});


	$('.toolbar select').removeAttr('onchange');
	$('body').on('change', '.limiter > select, .sort-by > select', function(e) {
		AjaxNavigation($(this), $(this).val());
		
		e.preventDefault();
	});
	
}); })(jQuery);