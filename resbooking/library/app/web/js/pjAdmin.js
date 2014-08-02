(function ($) {
	$(function () {
		var $frmLoginAdmin = $('#frmLoginAdmin'),
			$date = $("#date");
			
		/* $_GET Prefix */
		var $_GET = {}, $rbpf;

		document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		    function decode(s) {
		        return decodeURIComponent(s.split("+").join(" "));
		    }

		    $_GET[decode(arguments[1])] = decode(arguments[2]);
		});
		
		if ( $_GET['rbpf'] != null ) {
			
			$rbpf = "&rbpf=" + $_GET['rbpf'];
			
		} else if ( getCookie('rbpf') != '' ) {
			
			$rbpf = "&rbpf=" + getCookie('rbpf');
			
		} else $rbpf = '';
		
		function getCookie(cname)
		{
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++)
			  {
			  var c = ca[i].trim();
			  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
			  }
			return "";
		} 
		/* End Prefix */
		
		if ($date.length > 0) {
			$date.datepicker({			
				firstDay: $date.attr("rel"),
				dateFormat: $date.attr("rev"),
				showOn: "both",
				buttonImage: "app/web/img/icon-table.png",
				buttonImageOnly: true,
				onSelect: function (dateText, inst) {
					window.location.href = "index.php?controller=pjAdmin&action=dashboard&date=" + encodeURIComponent(dateText) + $rbpf;
				}
			});
		}
		
		$("#content").delegate("td.meta", "click", function () {
			var meta = $(this).parent().metadata();
			if (meta.id) {
				window.location.href = "index.php?controller=pjAdminBookings&action=update&id=" + meta.id + $rbpf;
			} else if (meta.space_id && meta.date) {
				window.location.href = ["index.php?controller=pjAdminBookings&action=index" + $rbpf + "&space_id=", meta.space_id, "&status=confirmed&date=", encodeURIComponent(meta.date)].join("");
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