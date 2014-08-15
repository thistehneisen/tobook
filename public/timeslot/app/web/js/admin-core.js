(function ($) {
	$(function () {
		$(".tsbc-table tbody tr").hover(
			function () {
				$(this).addClass("hover");
			}, 
			function () {
				$(this).removeClass("hover");
			}
		);
		$(".button").hover(
			function () {
				$(this).addClass("button_hover");
			}, 
			function () {
				$(this).removeClass("button_hover");
			}
		);
		if ($("#tsbc-calendar-id").length > 0) {
			$("#tsbc-calendar-id").change(function () {
				if ($(this).val() != "") {
					window.location.href = 'index.php?controller=AdminCalendars&action=view&cid=' + $(this).val();
				}
			});
		}
		$.ajaxSetup({
			jsonp: null,
			jsonpCallback: null
		});
	});
})(jQuery);