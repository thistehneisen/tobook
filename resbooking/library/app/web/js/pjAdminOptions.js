(function ($) {
	$(function () {
		var $tabs = $("#tabs"),
			$content = $("#content");
			
		$content.delegate(".textarea-install", "focus", function () {
			$(this).select();
		}).delegate(":input[name='value-enum-payment_enable_paypal']", "change", function () {
			var val = $(this).val(),
				$row = $(":input[name='value-string-paypal_address']").parent().parent();
			switch (val.split("::")[1]) {
				case "Yes":
					$row.show();
					break;
				case "No":
					$row.hide();
					break;	
			}
		}).delegate(":input[name='value-enum-payment_enable_authorize']", "change", function () {	
			var val = $(this).val(),
				$row1 = $(":input[name='value-string-payment_authorize_key']").parent().parent(),
				$row2 = $(":input[name='value-string-payment_authorize_mid']").parent().parent();
			switch (val.split("::")[1]) {
				case "Yes":
					$row1.show();
					$row2.show();
					break;
				case "No":
					$row1.hide();
					$row2.hide();
					break;	
			}
		});
		
		if ($tabs.length > 0) {
			$tabs.tabs({
				select: function(event, ui) {
					$(":input[name='tab_id']").val(ui.panel.id);
				} 
			});
		}
	});
})(jQuery);