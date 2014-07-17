(function ($) {
	$(function () {
		if ($("#tabs").length > 0) {
			$("#tabs").tabs({
				select: function(event, ui) {
					$(ui.panel).find(".tsbc-form").validate({
						errorClass: "error_clean"
					});
					$(":input[name='tab_id']").val(ui.panel.id);
				} 
			});
		}
		if ($(".textarea-install").length > 0) {
			$(".textarea-install").focus(function () {
				$(this).select();
			});
		}
		if ($('.colorSelector').length > 0) {
			var colorSelector = null;
			$('.colorSelector').ColorPicker({
				onBeforeShow: function () {
					colorSelector = $(this);
					$(this).ColorPickerSetColor(colorSelector.next('.hex').val());
					return false;
				},
				onShow: function (colpkr) {
					$(colpkr).fadeIn(300);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(300);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					colorSelector.children('div').eq(0).css('backgroundColor', '#' + hex);
					colorSelector.next('.hex').val(hex);
				}
			});
		}
		
		if ($(":input[name='value-enum-payment_enable_paypal']").length > 0) {
			$(":input[name='value-enum-payment_enable_paypal']").change(function () {
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
			});
		}
		
		if ($(":input[name='value-enum-payment_enable_authorize']").length > 0) {
			$(":input[name='value-enum-payment_enable_authorize']").change(function () {
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
		}		
	});
})(jQuery);