(function ($) {
	$(function () {
		
		var $tabs = $("#tabs"),
			$content = $("#content"),
			$dialogInstallContent = $("#dialogInstallContent");
			
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
		}).delegate("#installcontent", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			$dialogInstallContent.dialog("open");
			return false;
		});
		
		if ($dialogInstallContent.length > 0) {
			$dialogInstallContent.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 500,
				height: 600,
				open: function () {
					
					$.get("index.php?controller=pjAdminOptions&action=getposts" + $rbpf).done(function (data) {
						
						$dialogInstallContent.find('.content').html(data);
					});
				},
				buttons: {
					"Insert": function () {
						var $this = $(this),
							$post_id = $( "input.radio_postID:radio:checked" ).val();
						
						$.post("index.php?controller=pjAdminOptions&action=pjActionInsertContent" + $rbpf, {
							"id": $post_id
						}).done(function (data) {
							$this.dialog("close");
						});
					},
					'Close': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		$('body').on( "click", "#buttonSearchContent", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogInstallContent.length > 0) {
				var $search = $('input[name=search_posts]').val();
				
				$dialogInstallContent.find('.content').html("");
				$.get("index.php?controller=pjAdminOptions&action=getposts&search=" + $search + $rbpf).done(function (data) {
					$dialogInstallContent.find('.content').html(data);
				});
			}
			
			return false;
		});
		
		if ($tabs.length > 0) {
			
			var tOpt = {
					select: function (event, ui) {
						
						switch (ui.index) {
							case 7:
								window.location.href = "index.php?controller=pjAdminBookings&action=formstyle" + $rbpf;
								break;
						}
					}
				};
			
			$tabs.tabs(tOpt);
				
				var m = window.location.href.match(/&tab=(\d+)/);
				
				if (m && m.length === 2) {
					$tabs.tabs("option", "selected", parseInt(m[1], 10));
				}
			/*
			$tabs.tabs({
				select: function(event, ui) {
					$(":input[name='tab_id']").val(ui.panel.id);
				} 
			});*/
		}
	});
})(window.jQuery);