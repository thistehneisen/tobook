(function ($) {
	$(function () {
		var $frmLoginAdmin = $('#frmLoginAdmin'),
			$date = $("#date");
			
		if ($date.length > 0) {
			$date.datepicker({			
				firstDay: $date.attr("rel"),
				dateFormat: $date.attr("rev"),
				showOn: "both",
				buttonImage: "app/web/img/icon-table.png",
				buttonImageOnly: true,
				onSelect: function (dateText, inst) {
					window.location.href = "index.php?controller=pjAdmin&action=dashboard&date=" + encodeURIComponent(dateText);
				}
			});
		}
		
		$("#content").delegate("td.meta", "click", function () {
			var meta = $(this).parent().metadata();
			if (meta.id) {
				window.location.href = "index.php?controller=pjAdminBookings&action=update&id=" + meta.id;
			} else if (meta.space_id && meta.date) {
				window.location.href = ["index.php?controller=pjAdminBookings&action=index&space_id=", meta.space_id, "&status=confirmed&date=", encodeURIComponent(meta.date)].join("");
			}
		});
		
		if ($frmLoginAdmin.length > 0) {
			$frmLoginAdmin.validate({
				rules: {
					login_email: {
						required: true,
						email: true
					},
					login_password: "required"
				},
				errorContainer: $("#login-errors")
			});
		}
		
		var $frmUpdate = $("#frmUpdate");
		if ($frmUpdate.length > 0) {
			$frmUpdate.bind("submit", function (e) {
				$("input[type='submit']").prop("disabled", true);
			});
		}

	});
})(jQuery);