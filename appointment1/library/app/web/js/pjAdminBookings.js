var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var validator, $frmBooking,
			$frmCreateBooking = $("#frmCreateBooking"),
			$frmUpdateBooking = $("#frmUpdateBooking"),
			$dialogItemDelete = $("#dialogItemDelete"),
			$dialogItemAdd = $("#dialogItemAdd"),
			$dialogAddCustomer = $("#dialogAddCustomer"),
			$serviceItemAdd = $('#serviceItemAdd'),
			$dialogItemEmail = $("#dialogItemEmail"),
			$dialogItemDelete = $('#dialogItemDelete'),
			$dialogItemSms = $("#dialogItemSms"),
			$dialogExport = $("#dialogExport"),
			$dialogCustomerInfo = $("#dialogCustomerInfo"),
			$calendar = $('#calendar-month'),
			$monthly = $('#monthly-review'),
			$tabs = $("#tabs"),
			tabs = ($.fn.tabs !== undefined),
			dialog = ($.fn.dialog !== undefined),
			spinner = ($.fn.spinner !== undefined),
			validate = ($.fn.validate !== undefined),
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
		
		function getBookingItems(booking_id, tmp_hash) {
			$.get("index.php?controller=pjAdminBookings&action=pjActionItemGet" + $as_pf, {
				"booking_id": booking_id,
				"tmp_hash": tmp_hash
			}).done(function (data) {
				$("#boxBookingItems").html(data);
			});
		}
		
		if ($tabs.length > 0 && tabs) {
			$tabs.tabs();
		}
		
		if ($frmCreateBooking.length > 0 && validate) {
			
			$frmCreateBooking.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
			});
			$frmBooking = $frmCreateBooking;
		}
		
		if ($frmUpdateBooking.length > 0) {
			
			$frmUpdateBooking.on("click", ".btnCreateInvoice", function () {
				$("#frmCreateInvoice").trigger("submit");
			});
			
			if (validate) {
				$frmUpdateBooking.validate({
					errorPlacement: function (error, element) {
						error.insertAfter(element.parent());
					},
					onkeyup: false,
					errorClass: "err",
					wrapper: "em",
					ignore: ".ignore",
					invalidHandler: function (event, validator) {
					    if (validator.numberOfInvalids()) {
					    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();
					    	if ($tabs.length > 0 && tabs && index !== -1) {
					    		$tabs.tabs().tabs("option", "active", index-1);
					    	}
					    }
					}
				});
			}
			$frmBooking = $frmUpdateBooking;
			
			getBookingItems.call(null, $frmUpdateBooking.find("input[name='id']").val());
		}
		
		function formatDateTime(str) {
			if (str === null || str.length === 0) {
				return myLabel.empty_datetime;
			}
			
			if (str === '0000-00-00 00:00:00') {
				return myLabel.invalid_datetime;
			}
			
			if (str.match(/\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}/) !== null) {
				var x = str.split(" "),
					date = x[0],
					time = x[1],
					dx = date.split("-"),
					tx = time.split(":"),
					y = dx[0],
					m = parseInt(dx[1], 10) - 1,
					d = dx[2],
					hh = tx[0],
					mm = tx[1],
					ss = tx[2];
				return $.datagrid.formatDate(new Date(y, m, d, hh, mm, ss), pjGrid.jsDateFormat + ", hh:mm");
			}
		}
		
		function formatServices(str, obj) {
			var tmp,
				arr = [];
			for (var i = 0, iCnt = obj.items.length; i < iCnt; i++) {
				tmp = obj.items[i].split("~.~");
				arr.push([tmp[1], '<br /><a href="index.php?controller=pjAdminServices'+ $as_pf +'&action=pjActionUpdate&id=', tmp[0], '">', tmp[2], '</a>'].join(""));
			}
			
			return arr.join("<br />");
		}
		
		function formatClient (str, obj) {
			return [obj.c_name, 
			        (obj.c_email && obj.c_email.length > 0 ? ['<br><a href="mailto:', obj.c_email, '">', obj.c_email, '</a>'].join('') : ''), 
			        (obj.c_phone && obj.c_phone.length > 0 ? ['<br>', obj.c_phone].join('') : '')
			        ].join("");
		}
		
		function formatDefault (str) {
			return myLabel[str] || str;
		}
		
		function formatId (str) {
			return ['<a href="index.php?controller=pjInvoice'+ $as_pf +'&action=pjActionUpdate&id=', str, '">#', str, '</a>'].join("");
		}
		
		function formatTotal(val, obj) {
			return obj.total_formated;
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var options = {
				buttons: [{type: "edit", url: "index.php?controller=pjAdminBookings"+ $as_pf +"&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminBookings"+ $as_pf +"&action=pjActionDeleteBooking&id={:id}"}
				          ],
				columns: [{text: myLabel.uuid, type: "text", sortable: true, editable: false, width: 90},
				          {text: myLabel.services, type: "text", sortable: true, editable: false, renderer: formatServices, width: 175},
				          {text: myLabel.customer, type: "text", sortable: true, editable: false, renderer: formatClient},
				          {text: myLabel.total, type: "text", sortable: true, editable: false, align: "right", renderer: formatTotal},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, width: 90, options: [
				                                                                                     {label: myLabel.confirmed, value: 'confirmed'},
				                                                                                     {label: myLabel.pending, value: 'pending'},
				                                                                                     {label: myLabel.cancelled, value: 'cancelled'}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + $as_pf,
				dataType: "json",
				fields: ['uuid', 'id', 'c_name', 'booking_total', 'booking_status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk" + $as_pf, render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.export_selected, url: "javascript:void(0);", render: false, ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings"+ $as_pf +"&action=pjActionSaveBooking&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			};
			
			var cache = {},
				m1 = window.location.href.match(/&booking_status=(\w+)/),
				m2 = window.location.href.match(/&employee_id=(\d+)/);
			if (m1 !== null) {
				options.cache = $.extend(cache, {"booking_status" : m1[1]});
			}
			if (m2 !== null) {
				options.cache = $.extend(cache, {"employee_id" : m2[1]});
			}
			
			var $grid = $("#grid").datagrid(options);
		}
		
		if ($("#grid_invoices").length > 0 && datagrid) {
			var $grid_invoices = $("#grid_invoices").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjInvoice"+ $as_pf +"&action=pjActionUpdate&id={:id}", title: "Edit"},
				          {type: "delete", url: "index.php?controller=pjInvoice"+ $as_pf +"&action=pjActionDelete&id={:id}", title: "Delete"}],
				columns: [
				    {text: myLabel.num, type: "text", sortable: true, editable: false, renderer: formatId},
				    {text: myLabel.order_id, type: "text", sortable: true, editable: false},
				    {text: myLabel.issue_date, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				    {text: myLabel.due_date, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				    {text: myLabel.created, type: "text", sortable: true, editable: false, renderer: formatDateTime},
				    {text: myLabel.status, type: "text", sortable: true, editable: false, renderer: formatDefault},	
				    {text: myLabel.total, type: "text", sortable: true, editable: false, align: "right", renderer: formatTotal}
				],
				dataUrl: "index.php?controller=pjInvoice&action=pjActionGetInvoices&q=" + $frmUpdateBooking.find("input[name='uuid']").val() + $as_pf,
				dataType: "json",
				fields: ['id', 'order_id', 'issue_date', 'due_date', 'created', 'status', 'total'],
				paginator: {
					actions: [
					   {text: myLabel.delete_title, url: "index.php?controller=pjInvoice&action=pjActionDeleteBulk" + $as_pf, render: true, confirmation: myLabel.delete_body}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		if ($serviceItemAdd.length > 0) {
			
			$serviceItemAdd.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf, $frmBooking.find("input[name='id'], input[name='tmp_hash'], #date_from, #hour_from, #minute_from, #date_to, #hour_to, #minute_to").serialize()).done(function (data) {
				$serviceItemAdd.html(data);
				validator = $serviceItemAdd.find("form").validate(aiOpts);
					
				if ($('#serviceItemAddAjax').length > 0) {
					$('#serviceItemAddAjax').html($serviceItemAdd.html());
				}
			
			});
			
			$('body').on('click', '.serviceAdd', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				if (validator.form()) {
					$.post("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf, $serviceItemAdd.find("form").serialize()).done(function (data) {
						getBookingItems.call(null, $frmBooking.find("input[name='id']").val(), $frmBooking.find("input[name='tmp_hash']").val());
						
						$serviceItemAdd.html("");
						$serviceItemAdd.html($('#serviceItemAddAjax').html());
						validator = $serviceItemAdd.find("form").validate(aiOpts);
						
					});
				}
				
			}).on("change", "select[name='category_id']", function (e) {
				
				var $el = $(this),
					$form = $el.closest("form"),
					category_id = $form.find("select[name='category_id']").find("option:selected").val();
				
				$form.find("#bookingServices").html("");
				$form.find("#bookingServicesTime").html("").hide();
				$form.find(".item_details").html("").hide();
				
				$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, {
					"category_id": category_id
					
				}).done(function (data) {
					$form.find("#bookingServices").html( $(data).find(".bookingServices").html() );
				});
			
			}).on("change", "select[name='servicetime_id']", function (e) {
				var $el = $(this),
					$opt = {},
					$form = $el.closest("form"),
					service_id = $form.find("select[name='service_id']").find("option:selected").val(),
					servicetime_id = $form.find("select[name='servicetime_id']").find("option:selected").val(),
					selector = "input[name='employee_id'], input[name='start_ts'], input[name='end_ts']",
					$details = $form.find(".item_details");
					
				$details.html("").hide();
				
				if (parseInt(service_id, 10) > 0) {
					$form.find(selector).removeClass("ignore");
					$form.find(".bStartTime, .bEndTime, .bEmployee").show();
				} else {
					$form.find(selector).addClass("ignore");
					$form.find(".bStartTime, .bEndTime, .bEmployee").hide();
				}
				$form.find(selector).val("");
				$form.find(".data").text("---");
				
				$opt = {
					"id": service_id,
					"date": $form.find("input[name='date']").val()
				};
				
				if ( typeof servicetime_id != 'undefined' && servicetime_id > 0 ) {
					$opt["servicetime_id"] = servicetime_id;
				}
				
				$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, $opt ).done(function (data) {
					$details.html( $(data).find(".item_details").html() ).show();
				});
				
			}).on("change", "select[name='service_id'], input[name='date']", onChangeService);
		}
		
		$("#dialogAddCustomer").on("click", ".buttonSearchCustomerInfo", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($('#boxCustomerInfo').length > 0) {
				var $search = $('input[name=search_customer]').val();
				
				$('#boxCustomerInfo').html("");
				$.get("index.php?controller=pjAdminBookings&action=pjActionSearchCustomer&search=" + $search + $as_pf).done(function (data) {
					$('#boxCustomerInfo').html(data);
				});
			}
			
			return false;
		});
		
		$("#content").on("click", ".item-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogItemAdd.length > 0 && dialog) {
				$dialogItemAdd.dialog("open");
				
			}
			
			return false;
		}).on("click", ".addCustomerInfo", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogAddCustomer.length > 0 && dialog) {
				$dialogAddCustomer.dialog("open");
				
			}
			 
			return false;
		}).on("click", ".customer-edit", function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogCustomerInfo.length > 0 && dialog) {
				$dialogCustomerInfo.data("id", $(this).data("id")).dialog("open");
				
			}
			
			return false;
		}).on("click", ".item-delete", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogItemDelete.length > 0 && dialog) {
				$dialogItemDelete.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".item-email", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogItemEmail.length > 0 && dialog) {
				$dialogItemEmail.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".item-sms", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogItemSms.length > 0 && dialog) {
				$dialogItemSms.data("id", $(this).data("id")).dialog("open");
			}
			return false;
		}).on("click", ".order-calc", function () {
			var $this = $(this),
				$form = $this.closest("form");
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetPrice" + $as_pf, $form.serialize()).done(function (data) {
				if (data.status == 'OK') {
					$form.find("#booking_price").val(data.data.price.toFixed(2));
					$form.find("#booking_deposit").val(data.data.deposit.toFixed(2));
					$form.find("#booking_tax").val(data.data.tax.toFixed(2));
					$form.find("#booking_total").val(data.data.total.toFixed(2));
				}
			});
		}).on("change", "#payment_method", function () {
			if ($("option:selected", this).val() == 'creditcard') {
				$(".erCC").show();
			} else {
				$(".erCC").hide();
			}
		});
		
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
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				booking_status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			obj.booking_status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + $as_pf, "id", "DESC", content.page, content.rowCount);
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
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			cache.q = "";
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + $as_pf, "id", "DESC", content.page, content.rowCount);
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(".pj-button-detailed").trigger("click");
			return false;
		}).on("click.as", ".asSlotAvailable", function (e) {	
			
			var $this = $(this),
				$form = $this.closest("form");
			
			if ($this.hasClass("asSlotSelected")) {
				$this.removeClass("asSlotSelected");
				
				$form.find("input[name='employee_id']").val("");
				$form.find("input[name='start_ts']").val("");
				$form.find("input[name='end_ts']").val("");
				
				$form.find(".data").text("---");
			} else {
				$form.find(".asSlotBlock").removeClass("asSlotSelected");
				$this.addClass("asSlotSelected");
				
				$form.find("input[name='employee_id']").val($this.data("employee_id"));
				$form.find("input[name='start_ts']").val($this.data("start_ts"));
				$form.find("input[name='end_ts']").val($this.data("end_ts"));
				
				$form.find(".bStartTime .data").text($this.text());
				$form.find(".bEndTime .data").text($this.data("end"));
				$form.find(".bEmployee .data").text($this.closest(".asElement").find(".asEmployeeName").text());
			}
		});
		
		$("#grid").on("click", "a.pj-paginator-action:last", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($(".pj-table-select-row:checked").length > 0 && $dialogExport.length > 0 && dialog) {
				$dialogExport.dialog("open");
				$(this).closest(".pj-menu-list-wrap").hide();
			}
			return false;
		});
		
		function onChange() {
			var $el = $(this),
				$form = $el.closest("form"),
				service_id = $form.find("select[name='service_id']").find("option:selected").val(),
				selector = "input[name='employee_id'], input[name='start_ts'], input[name='end_ts']",
				$dialog = $form.parent(),
				$details = $form.find(".item_details");
			
			if (parseInt(service_id, 10) > 0) {
				$form.find(selector).removeClass("ignore");
				$form.find(".bStartTime, .bEndTime, .bEmployee").show();
			} else {
				$form.find(selector).addClass("ignore");
				$form.find(".bStartTime, .bEndTime, .bEmployee").hide();
			}
			$form.find(selector).val("");
			$form.find(".data").text("---");
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, {
				"id": service_id,
				"date": $form.find("input[name='date']").val()
			}).done(function (data) {
				$details.html(data).show();
				$dialog.dialog("option", "position", "center");
			});
		}

		function onChangeService() {
			var $el = $(this),
				$opt = {},
				$form = $el.closest("form"),
				service_id = $form.find("select[name='service_id']").find("option:selected").val(),
				selector = "input[name='employee_id'], input[name='start_ts'], input[name='end_ts']",
				$details = $form.find(".item_details");
			
			$details.html("").hide();
			
			if (parseInt(service_id, 10) > 0) {
				$form.find(selector).removeClass("ignore");
				$form.find(".bStartTime, .bEndTime, .bEmployee").show();
			} else {
				$form.find(selector).addClass("ignore");
				$form.find(".bStartTime, .bEndTime, .bEmployee").hide();
			}
			$form.find(selector).val("");
			$form.find(".data").text("---");
			
			$opt = {
				"id": service_id,
				"date": $form.find("input[name='date']").val()
			};
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, $opt ).done(function (data) {
				$details.html( $(data).find(".item_details").html() ).show();
				$form.find("#bookingServicesTime").html( $(data).find(".bookingServicesTime").html() ).show();
			});
		}
		
		var aiOpts = {
			rules: {
				"date": "required",
				/*"service_id": {
					required: true,
					digits: true
				},*/
				"employee_id": {
					required: true,
					digits: true
				},
				"start_ts": {
					required: true,
					digits: true
				},
				"end_ts": {
					required: true,
					digits: true
				}
			},
			ignore: ".ignore"
		};
		
		if ($dialogItemAdd.length > 0 && dialog) {
			$dialogItemAdd.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 600,
				open: function () {
					$dialogItemAdd.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf, $frmBooking.find("input[name='id'], input[name='tmp_hash'], #date_from, #hour_from, #minute_from, #date_to, #hour_to, #minute_to").serialize()).done(function (data) {
						$dialogItemAdd.html(data);
						validator = $dialogItemAdd.find("form").validate(aiOpts);
						$dialogItemAdd.dialog("option", "position", "center");
					});
				},
				buttons: {
					"Add": function () {
						var $this = $(this);
						if (validator.form()) {
							$.post("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf, $dialogItemAdd.find("form").serialize()).done(function (data) {
								getBookingItems.call(null, $frmBooking.find("input[name='id']").val(), $frmBooking.find("input[name='tmp_hash']").val());
								$this.dialog("close");
							});
						}
					},
					"Cancel": function () {
						$(this).dialog("close");
					}
				}
			}).on("change", "select[name='service_id'], input[name='date']", onChange);
		}
		
		if ($dialogAddCustomer.length > 0 && dialog) {
			$dialogAddCustomer.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 600,
				open: function () {
					if ($('#boxCustomerInfo').length > 0) {
						$('#boxCustomerInfo').html("");
						$.get("index.php?controller=pjAdminBookings&action=pjActionSearchCustomer" + $as_pf).done(function (data) {
							$('#boxCustomerInfo').html(data);
						});
					}
				},
				buttons: {
					"Add": function () {
						var $parentClass = $('.' + $( "input[name=radio_customer]:radio:checked" ).val());
						$('#c_name').val($parentClass.find('.customerName').text());
						$('#c_email').val($parentClass.find('.customerEmail').text());
						$('#c_phone').val($parentClass.find('.customerPhone').text());
						
						$('input[name=search_customer]').val('');
						$(this).dialog("close");
					},
					"Cancel": function () {
						$('input[name=search_customer]').val('');
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogCustomerInfo.length > 0 && dialog) {
			$dialogCustomerInfo.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 700,
				open: function () {
					$dialogCustomerInfo.html("");
					var $this = $(this);
					$.get("index.php?controller=pjAdminBookings&action=pjActionUpdate&customer=1" + $as_pf, {
						"id": $this.data("id")
					}).done(function (data) {
						$dialogCustomerInfo.html($(data).find('#frmUpdateBooking'));
						validator = $dialogCustomerInfo.find("form").validate(aiOpts);
						$dialogCustomerInfo.dialog("option", "position", "center");
					});
					
					$("body").on("click", ".notice-close", function (e) {
						if (e && e.preventDefault) {
							e.preventDefault();
						}
						$('body').find('.notice-box').fadeOut();
						return false;
					});
					
				},
			});
		}
		
		if ($dialogItemDelete.length > 0 && dialog) {
			$dialogItemDelete.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				buttons: {
					"Delete": function () {
						var $this = $(this);
						$.post("index.php?controller=pjAdminBookings&action=pjActionItemDelete" + $as_pf, {
							"id": $this.data("id")
						}).done(function (data) {
							getBookingItems.call(null, $frmBooking.find("input[name='id']").val(), $frmBooking.find("input[name='tmp_hash']").val());
							$this.dialog("close");
						});
					},
					"Cancel": function () {
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
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
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
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
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
		
		if ($dialogExport.length > 0 && dialog) {
			$dialogExport.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				open: function () {
					$dialogExport.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionExport" + $as_pf).done(function (data) {
						$dialogExport.html(data);
						$dialogExport.dialog("option", "position", "center");
					});
				},
				buttons: {
					"Export": function () {
						var i, iCnt,
							$form = $dialogExport.find("form"),
							records = $grid.find(".pj-table-select-row").serializeArray();
						
						for (i = 0, iCnt = records.length; i < iCnt; i++) {
							$("<input>", {
								"type": "hidden",
								"name": records[i].name,
								"value": records[i].value
							}).appendTo($form);
						}
						
						$form.get(0).submit();
						$dialogExport.dialog("close");
					},
					"Cancel": function () {
						$(this).dialog("close");
					}
				}
			}).on("change", "select[name='format']", function () {
				if ($(this).find("option:selected").val() == "csv") {
					$dialogExport.find(".csvRelated").show();
				} else {
					$dialogExport.find(".csvRelated").hide();
				}
			});
		}
		
		if ($calendar.length >0 ) {
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetCalendar" + $as_pf).done(function (data) {
				$calendar.html(data);
				
			});
		}
		
		if ($monthly.length >0 ) {
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetMonthly" + $as_pf).done(function (data) {
				$monthly.html(data);
				
			});
		}

		$('body').on('click', '.calendar-control', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}

			var $url = $(this).attr('href');
			
			$.get($url).done(function (data) {
				$calendar.html(data);
			});
				
			return false;
		}).on('change', '#calendar select[name="employee_id"]', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}

			$calendar.html("Loading ...");
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetCalendar&employee_id=" + $(this).val() + $as_pf).done(function (data) {
				$calendar.html(data);
			});
				
			return false;
		}).on('click', '.monthly-control', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $month,
				$m = $(this).attr('data-m');
			
			if ( typeof $m === "undefined" ) 
				$month = '';
			
			else $month = '&m=' + $m;
			
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetMonthly" + $as_pf + $month).done(function (data) {
				$monthly.html(data);
				
			});
			
		}).on('change', '#monthly-review select[name="employee_id"]', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}

			$.get("index.php?controller=pjAdminBookings&action=pjActionGetMonthly&employee_id=" + $(this).val() + $as_pf).done(function (data) {
				$monthly.html(data);
			});
				
			return false;
		});
		
		$( document ).ajaxStart(function() {
			$( "#loading" ).show();
		});
		
		$( document ).ajaxStop(function() {
			$( "#loading" ).hide();
		});
		
	});
})(jQuery_1_8_2);