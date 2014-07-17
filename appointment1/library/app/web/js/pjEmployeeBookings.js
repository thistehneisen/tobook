var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var validator,
			$dialogView = $("#dialogView"),
			$dialogItemEmail = $("#dialogItemEmail"),
			$dialogItemSms = $("#dialogItemSms"),
			dialog = ($.fn.dialog !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			datepicker = ($.fn.datepicker !== undefined);
		
		/* $_GET Prefix */
		var $_GET = {}, $as_pf;

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
		
		function formatTime(str, obj) {
			return $.datagrid.formatDate(new Date(obj.time), pjGrid.jsDateFormat + ", hh:mm");
		}
		
		function formatClient (str, obj) {
			return [obj.c_name, 
			        (obj.c_email && obj.c_email.length > 0 ? ['<br><a href="mailto:', obj.c_email, '">', obj.c_email, '</a>'].join('') : ''), 
			        (obj.c_phone && obj.c_phone.length > 0 ? ['<br>', obj.c_phone].join('') : '')
			        ].join("");
		}
		
		function formatCustom(str, obj) {
			return ['<a href="#" class="pj-table-icon-email item-email"></a><a href="#" class="pj-table-icon-phone item-sms"></a>'].join('');
		}
		
		if ($("#grid_list").length > 0 && datagrid) {
			
			var $grid_list = $("#grid_list").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"}],
				columns: [{text: myLabel.uuid, type: "text", sortable: true, editable: false, width: 90},
				          {text: myLabel.service, type: "text", sortable: true, editable: false, width: 150},
				          {text: myLabel.dt, type: "text", sortable: true, editable: false, renderer: formatTime, width: 110},
				          {text: myLabel.customer, type: "text", sortable: true, editable: false, renderer: formatClient},
				          {text: myLabel.status, type: "select", sortable: true, editable: false, width: 90, options: [
				                                                                                     {label: myLabel.confirmed, value: 'confirmed'},
				                                                                                     {label: myLabel.pending, value: 'pending'},
				                                                                                     {label: myLabel.cancelled, value: 'cancelled'}
				                                                                                     ], applyClass: "pj-status"},
				          {text: "", type: "text", sortable: false, editable: false, renderer: formatCustom}
				],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBookingService" + $as_pf,
				dataType: "json",
				fields: ['uuid', 'service_name', 'time', 'c_name', 'booking_status', 'id']
			});
		}
		
		$(document).on("focusin", ".datepick", function (e) {
			var $this = $(this);
			$this.datepicker({
				firstDay: $this.attr("rel"),
				dateFormat: $this.attr("rev")
			});
		}).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid_list.datagrid("option", "content"),
				cache = $grid_list.datagrid("option", "cache");
			$.extend(cache, {
				booking_status: "",
				q: ""
			});
			$grid_list.datagrid("option", "cache", cache);
			$grid_list.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBookingService" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid_list.datagrid("option", "content"),
				cache = $grid_list.datagrid("option", "cache"),
				obj = {};
			$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			obj.booking_status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid_list.datagrid("option", "cache", cache);
			$grid_list.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBookingService" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid_list.datagrid("option", "content"),
				cache = $grid_list.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid_list.datagrid("option", "cache", cache);
			$grid_list.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBookingService" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		}).on("click", ".pj-button-detailed, .pj-button-detailed-arrow", function (e) {
			e.stopPropagation();
			$(".pj-form-filter-advanced").slideToggle();
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = {},
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid_list.datagrid("option", "content"),
				cache = $grid_list.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			cache.q = "";
			$.extend(cache, obj);
			$grid_list.datagrid("option", "cache", cache);
			$grid_list.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBookingService" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(".pj-button-detailed").trigger("click");
			return false;
		}).on("click", ".pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if (dialog && $dialogView.length > 0) {
				$dialogView.data("id", $(this).data("id").id).dialog("open");
			}
			return false;
		}).on("click", ".employee-booking", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if (dialog && $dialogView.length > 0) {
				$dialogView.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".item-email", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogItemEmail.length > 0 && dialog) {
				$dialogItemEmail.data("id", $(this).closest("tr").data("object").id).dialog("open");
			}
			return false;
		}).on("click", ".item-sms", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogItemSms.length > 0 && dialog) {
				$dialogItemSms.data("id", $(this).closest("tr").data("object").id).dialog("open");
			}
			return false;
		});
		
		if ($dialogView.length > 0 && dialog) {
			$dialogView.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 700,
				open: function () {
					$dialogView.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionViewBookingService" + $as_pf, {
						"id": $dialogView.data("id")
					}).done(function (data) {
						$dialogView.html(data);
						$dialogView.dialog("option", "position", "center");
					});
				},
				buttons: {
					"Close": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogItemEmail.length > 0 && dialog) {
			$dialogItemEmail.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 640,
				open: function () {
					$dialogItemEmail.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionItemEmail" + $as_pf, {
						"id": $dialogItemEmail.data("id")
					}).done(function (data) {
						$dialogItemEmail.html(data);
						validator = $dialogItemEmail.find("form").validate({
							errorClass: "error_clean"
						});
						$dialogItemEmail.dialog("option", "position", "center");
					});
				},
				buttons: {
					"Send": function () {
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionItemEmail" + $as_pf, $dialogItemEmail.find("form").serialize()).done(function (data) {
								$dialogItemEmail.dialog("close");
							});
						}
					},
					"Cancel": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogItemSms.length > 0 && dialog) {
			$dialogItemSms.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 640,
				open: function () {
					$dialogItemSms.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionItemSms" + $as_pf, {
						"id": $dialogItemSms.data("id")
					}).done(function (data) {
						$dialogItemSms.html(data);
						validator = $dialogItemSms.find("form").validate({
							errorClass: "error_clean"
						});
						$dialogItemSms.dialog("option", "position", "center");
					});
				},
				buttons: {
					"Send": function () {
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionItemSms" + $as_pf, $dialogItemSms.find("form").serialize()).done(function (data) {
								$dialogItemSms.dialog("close");
							});
						}
					},
					"Cancel": function () {
						$(this).dialog("close");
					}
				}
			});
		}
	});
})(jQuery_1_8_2);