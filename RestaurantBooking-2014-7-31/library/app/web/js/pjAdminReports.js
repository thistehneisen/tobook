(function ($) {
	$(function () {
		var $datepick = $(".datepick");
		if ($datepick.length > 0) {
			$datepick.datepicker({
				firstDay: $datepick.attr("rel"),
				dateFormat: $datepick.attr("rev"),
				onSelect: function(dateText, inst) {
					var option, pairId, 
						m = this.id.match(/date_(from|to)/),
						date = $.datepicker.parseDate($datepick.attr("rev"), dateText, inst.settings);
					if (m[1] == "from") {
						option = "minDate";
						pairId = "#date_to";
					} else {
						option = "maxDate";
						pairId = "#date_from";
					}
					$(pairId).datepicker("option", option, date);
				}
			});
		}
	});
})(jQuery);