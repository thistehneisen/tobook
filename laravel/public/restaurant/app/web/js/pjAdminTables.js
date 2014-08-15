(function ($) {
	$(function () {
	
		var $frmCreateTable = $('#frmCreateTable'),
			$frmUpdateTable = $('#frmUpdateTable'),
			$tabs = $("#tabs"),
			$dialogDelete = $("#dialogDelete"),
			$content = $("#content"),
			$datepick = $(".datepick"),
			dOpts = {};
			
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
		});
		
		if ($frmCreateTable.length > 0) {
			$frmCreateTable.validate();
		}
		
		if ($frmUpdateTable.length > 0) {
			$frmUpdateTable.validate();
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
						$.post("index.php?controller=pjAdminTables&action=delete" + $rbpf, {
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