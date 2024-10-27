(function($) {
	$(document).ready(function() {
		var open = false;
		$dimensions_btn = $('#addart_artshow_expand_btn');
		$dimensions = $('#addart_artshow_expand');
		$dimensions_btn.click(function() {
			if(open == false) {
				open = true;
				$dimensions.css({'display':'block'});
				$dimensions_btn.html('Hide dimensions');
			} else {
				open = false;
				$dimensions.css({'display':'none'});
				$dimensions_btn.html('Show dimensions');
			}
		});
	});
})(jQuery);