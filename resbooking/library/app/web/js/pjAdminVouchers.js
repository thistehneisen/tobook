(function ($) {
	$(function () {
	
		var $frmCreateVoucher = $('#frmCreateVoucher'),
			$frmUpdateVoucher = $('#frmUpdateVoucher'),
			$tabs = $("#tabs"),
			$dialogDelete = $("#dialogDelete"),
			$content = $("#content"),
			$datepick = $(".datepick"),
			dOpts = {};
			
		if ($datepick.length > 0) {
			dOpts = $.extend(dOpts, {
				firstDay: $datepick.attr("rel"),
				dateFormat: $datepick.attr("rev")
			});
		}
			
		$content.delegate("a.icon-delete", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$('#dialogDelete').data('id', $(this).attr("rel")).dialog('open');
			return false;
		}).delegate(".datepick", "focusin", function (e) {
			$(this).datepicker(dOpts);
		}).delegate("#valid", "change", function () {
			switch ($("option:selected", this).val()) {
				case 'fixed':
					$(".vBox").hide();
					$("#vFixed").show();
					break;
				case 'period':
					$(".vBox").hide();
					$("#vPeriod").show();
					break;
				case 'recurring':
					$(".vBox").hide();
					$("#vRecurring").show();
					break;
			}
		});
		
		if ($frmCreateVoucher.length > 0) {
			$frmCreateVoucher.validate();
		}
		
		if ($frmUpdateVoucher.length > 0) {
			$frmUpdateVoucher.validate();
		}
		
		if ($tabs.length > 0) {
			$tabs.tabs();
		}
		
		if ($dialogDelete.length > 0) {
			$dialogDelete.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				buttons: {
					'Delete': function() {
						$.post("index.php?controller=pjAdminVouchers&action=delete", {
							id: $(this).data('id')
						}).done(function (data) {
							$content.html(data);
							$("#tabs").tabs();
						});
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
		
	});
})(jQuery);