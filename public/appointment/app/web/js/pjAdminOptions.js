var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $tabs = $("#tabs"),
			$dialogInstallContent = $("#dialogInstallContent"),
			dialog = ($.fn.dialog !== undefined),
			tabs = ($.fn.tabs !== undefined);

		if ($tabs.length > 0 && tabs) {
			$tabs.tabs();
		}
		
		$(".field-int").spinner({
			min: 0
		});
		
		/* $_GET Prefix */
		var $_GET = {}, $as_pf = '';

		document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		    function decode(s) {
		        return decodeURIComponent(s.split("+").join(" "));
		    }

		    $_GET[decode(arguments[1])] = decode(arguments[2]);
		});
		
		if ( $_GET['as_pf'] != null ) {
			
			$as_pf = "&as_pf=" + $_GET['as_pf'];
			
		} else if ( getCookie('as_pf') != '' ) {
			
			$as_pf = "&as_pf=" + getCookie('as_pf');
			
		} else $as_pf = '';
		
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
		
		function reDrawCode() {
			var code = $("#hidden_code").text(),
				locale = $("select[name='install_locale']").find("option:selected").val(),
				hide = $("input[name='install_hide']").is(":checked") ? "&hide=1" : "";
			locale = parseInt(locale.length, 10) > 0 ? "&locale=" + locale : "";
						
			$("#install_code").text(code.replace(/&action=pjActionLoad"/g, function(match) {
	            return ['&action=pjActionLoad', locale, hide, '"'].join("");
	        }));
		}
		
		$("#content").on("focus", ".textarea_install", function (e) {
			var $this = $(this);
			$this.select();
			$this.mouseup(function() {
				$this.unbind("mouseup");
				return false;
			});
		}).on("keyup", "#uri_page", function (e) {
			var tmpl = $("#hidden_htaccess").text(),
				index = this.value.indexOf("?");
			$("#install_htaccess").text(tmpl.replace('::URI_PAGE::', index >= 0 ? this.value.substring(0, index) : this.value));
		}).on("change", "select[name='install_locale']", function(e) {
            
            reDrawCode.call(null);
            
		}).on("change", "input[name='install_hide']", function (e) {
			
			reDrawCode.call(null);
			
		}).on("change", "select[name='value-enum-o_send_email']", function (e) {
			switch ($("option:selected", this).val()) {
			case 'mail|smtp::mail':
				$(".boxSmtp").hide();
				break;
			case 'mail|smtp::smtp':
				$(".boxSmtp").show();
				break;
			}
		}).on("change", "input[name='value-bool-o_allow_paypal']", function (e) {
			if ($(this).is(":checked")) {
				$(".boxPaypal").show();
			} else {
				$(".boxPaypal").hide();
			}
		}).on("change", "input[name='value-bool-o_allow_authorize']", function (e) {
			if ($(this).is(":checked")) {
				$(".boxAuthorize").show();
			} else {
				$(".boxAuthorize").hide();
			}
		}).on("change", "input[name='value-bool-o_allow_bank']", function (e) {
			if ($(this).is(":checked")) {
				$(".boxBank").show();
			} else {
				$(".boxBank").hide();
			}
		}).on("click", "#installcontent", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			$dialogInstallContent.dialog("open");
			return false;
		});
		
		if ($dialogInstallContent.length > 0) {
			$dialogInstallContent.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 500,
				open: function () {
					
					$.get("index.php?controller=pjAdminOptions&action=getposts" + $as_pf).done(function (data) {
						
						$dialogInstallContent.find('.content').html(data);
					});
				},
				buttons: {
					"Insert": function () {
						var $this = $(this),
							$post_id = $( "input.radio_postID:radio:checked" ).val();
						
						$.post("index.php?controller=pjAdminOptions&action=pjActionInsertContent" + $as_pf, {
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
				
				$dialogInstallContent.find('.content').html("Loading...");
				$.get("index.php?controller=pjAdminOptions&action=getposts&search=" + $search + $as_pf).done(function (data) {
					$dialogInstallContent.find('.content').html(data);
				});
			}
			
			return false;
		});
		
	});
})(jQuery_1_8_2);
