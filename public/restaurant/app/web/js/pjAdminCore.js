(function ($) {
	$(function () {
		$(".table tbody tr").hover(
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
	});
})(jQuery);