var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateEmployee = $("#frmCreateEmployee"),
			$frmCustomtime = $('#frmCustomtime'),
			$frmFreetime = $('#frmFreetime'),
			$frmUpdateEmployee = $("#frmUpdateEmployee"),
			$dialogDeleteAvatar = $("#dialogDeleteAvatar"),
			$monthly = $('#monthly-review'),
			multiselect = ($.fn.multiselect !== undefined),
			dialog = ($.fn.dialog !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			validate = ($.fn.validate !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			vOpts;
		
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
		
		var $owner_id = 0;
		if ( $_GET['as_pf'] != null ) {
			$owner_id = '&owner_id=' + $_GET['owner_id'];
		}

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
		
		vOpts = {
			rules: {
				/*email: {
					required: true,
					email: true,
					remote: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionCheckEmail"
				}*/
			},
			messages: {
				email: {
					remote: myLabel.email_taken
				}
			},
			errorPlacement: function (error, element) {
				error.insertAfter(element.parent());
			},
			onkeyup: false,
			errorClass: "err",
			wrapper: "em"
		};
		
		if ($frmCreateEmployee.length > 0) {
			$frmCreateEmployee.validate(vOpts);
		}
		if ($frmUpdateEmployee.length > 0) {
			vOpts.rules.email.remote = vOpts.rules.email.remote + "&id=" + $frmUpdateEmployee.find("input[name='id']").val();
			$frmUpdateEmployee.validate(vOpts);
		}
		if ($frmCustomtime.length > 0) {
			$frmCustomtime.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if ($frmFreetime.length > 0) {
			$frmFreetime.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if (multiselect) {
			$("select[name^='service_id']").multiselect({
				show: ['fade', 250],
				hide: ['fade', 250]
			});
		}
		
		function formatAvatar(path, obj) {
			var src = 'app/web/img/frontend/as-nopic-blue.gif';
			if (path !== null && path.length > 0) {
				src = path;
			}
			return ['<a href="index.php?controller=pjAdminEmployees'+ $as_pf +'&action=pjActionUpdate&id=', obj.id, '"><img src="', src, '" alt="" class="as-avatar" /></a>'].join('');
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var options = {}
			
			if ( window.location.href.match(/&action=pjActionFreetime/) !== null ) {
				
				options = {
						buttons: [{type: "edit", url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionFreetime&id={:id}"},
						          {type: "delete", url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteEmployeeFreetime&id={:id}"},
						          ],
						columns: [{text: myLabel.name, type: "text", sortable: true, editable: true },
						          {text: myLabel.date, type: "text", sortable: true, editable: true},
						          {text: myLabel.start_ts, type: "text", sortable: true, editable: true},
						          {text: myLabel.end_ts, type: "text", sortable: true, editable: true},
						          {text: myLabel.message, type: "text", sortable: true, editable: true},
						          ],
								  
						dataUrl: "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee&tyle=freetime" + $owner_id + $as_pf,
						dataType: "json",
						fields: ['name', 'date', 'start_ts', 'end_ts', 'message'],
						paginator: {
							actions: [
							   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteEmployeeFreetimeBulk", render: true, confirmation: myLabel.delete_confirmation}
							],
							gotoPage: true,
							paginate: true,
							total: true,
							rowCount: true
						},
						saveUrl: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionSaveEmployeeFreetime&id={:id}",
						select: {
							field: "id",
							name: "record[]"
						}
					};
				
			} else if ( window.location.href.match(/&action=pjActionCustomTimes/) !== null ) {
					
				options = {
					buttons: [{type: "edit", url: "index.php?controller=pjAdminEmployees&action=pjActionCustomTimes"+ $as_pf +"&id={:id}"},
					          {type: "delete", url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteCustomtime&id={:id}"}
					          ],
					columns: [{text: myLabel.name, type: "text", sortable: true, editable: true },
					          {text: myLabel.time_start, type: "text", sortable: true, editable: false, width: 100},
					          {text: myLabel.time_end, type: "text", sortable: true, editable: false, width: 100},
					          {text: myLabel.time_lunch_start, type: "text", sortable: true, editable: false, width: 100},
					          {text: myLabel.time_lunch_end, type: "text", sortable: true, editable: false, width: 100},
					          {text: myLabel.time_dayoff, type: "select", sortable: true, editable: true, options: [
				     				       {label: myLabel.time_yesno.T, value: 'T'}, 
				     				       {label: myLabel.time_yesno.F, value: 'F'}
				     				       ], applyClass: "pj-status"}],
					dataUrl: "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee&tyle=customtime" + $as_pf,
					dataType: "json",
					fields: ['name', 'start_time', 'end_time', 'start_lunch', 'end_lunch', 'is_dayoff'],
					paginator: {
						actions: [
						   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteCustomtimeBulk", render: true, confirmation: myLabel.delete_confirmation}
						],
						gotoPage: true,
						paginate: true,
						total: true,
						rowCount: true
					},
					
					saveUrl: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionSaveCustomtime&id={:id}",
					select: {
						field: "id",
						name: "record[]"
					}
				};
					
			} else {
				options = {
					buttons: [{type: "edit", url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionUpdate&id={:id}"},
					          {type: "delete", url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteEmployee&id={:id}"},
					          {type: "menu", url: "#", text: myLabel.menu, items: [
					                {text: myLabel.view_bookings, url: "index.php?controller=pjAdminBookings"+ $as_pf +"&action=pjActionIndex&employee_id={:id}"}, 
					                {text: myLabel.working_time, url: "index.php?controller=pjAdminTime"+ $as_pf +"&action=pjActionIndex&type=employee&foreign_id={:id}"}
					             ]
					          }
					          ],
					columns: [{text: myLabel.avatar, type: "text", sortable: true, editable: false, width: 75, renderer: formatAvatar},
					          {text: myLabel.name, type: "text", sortable: true, editable: true, width: 125, editableWidth: 110},
					          {text: myLabel.email, type: "text", sortable: true, editable: true, width: 125, editableWidth: 110},
					          {text: myLabel.phone, type: "text", sortable: true, editable: true, width: 90, editableWidth: 80},
					          {text: myLabel.services, type: "text", sortable: true, editable: false, align: "center", width: 70},
					          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [
							                                                                                     {label: myLabel.active, value: 1}, 
							                                                                                     {label: myLabel.inactive, value: 0}
							                                                                                     ], applyClass: "pj-status"}],
					dataUrl: "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee" + $as_pf + $owner_id,
					dataType: "json",
					fields: ['avatar', 'name', 'email', 'phone', 'services', 'is_active'],
					paginator: {
						actions: [
						   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionDeleteEmployeeBulk", render: true, confirmation: myLabel.delete_confirmation}
						],
						gotoPage: true,
						paginate: true,
						total: true,
						rowCount: true
					},
					saveUrl: "index.php?controller=pjAdminEmployees"+ $as_pf +"&action=pjActionSaveEmployee&id={:id}",
					select: {
						field: "id",
						name: "record[]"
					}
				};
				
			}
			
			var m = window.location.href.match(/&is_active=(\d+)/);
			if (m !== null) {
				options.cache = {"is_active" : m[1]};
			}
			
			var $grid = $("#grid").datagrid(options);
		}
		
		$("#content").on("click", ".btnDeleteAvatar", function () {
			if ($dialogDeleteAvatar.length > 0 && dialog) {
				$dialogDeleteAvatar.data("id", $frmUpdateEmployee.find("input[name='id']").val()).dialog("open");
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
				is_active: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee" + $as_pf, "name", "ASC", content.page, content.rowCount);
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
			obj.is_active = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee" + $as_pf, "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminEmployees&action=pjActionGetEmployee" + $as_pf, "id", "ASC", content.page, content.rowCount);
			return false;
		});
		
		$("body").on("change", "#frmFreetime select[name='employee_id'], input[name='date']", onChange);
		
		function onChange() {
			var $el = $(this),
				$form = $el.closest("form"),
				employee_id = $form.find("select[name='employee_id']").find("option:selected").val();
				
			$.get("index.php?controller=pjAdminEmployees&action=pjActionFreetime" + $as_pf, {
				"employee_id": employee_id,
				"date": $form.find("input[name='date']").val()
			}).done(function (data) {
				$('#loadajaxtime').html($(data).find('#loadajaxtime').html());
			});
		}
		
		if ($dialogDeleteAvatar.length > 0 && dialog) {
			$dialogDeleteAvatar.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					"Yes": function () {
						$.post("index.php?controller=pjAdminEmployees&action=pjActionDeleteAvatar" + $as_pf, {
							"id": $dialogDeleteAvatar.data("id")
						}).done(function(data) {
							
							if (data.status == "OK") {
								$(".boxAvatar").html("").hide();
								$(".boxNoAvatar").show();
							}
							
							$dialogDeleteAvatar.dialog("close");
						});
					},
					"No": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		$('body').on('click', 'a.monthly-control', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var $date = $(this).attr('data-date');
			
			if ( typeof $date === "undefined" ) {
				return false;
				
			} else {
			
				var $href = window.location.href.replace(/&date=(.*)&/,'&');
				
				$href = $href.replace(/&date=(.*)/,'');
				
				window.location.href = $href +'&date=' + $date;
			}
			
		}).on('change', '#monthly-review select[name="employee_id"]', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}

			var $employee_id = $(this).val();
			
			if ( typeof $employee_id === "undefined" ) {
				return false;
				
			} else {
			
				var $href = window.location.href.replace(/&employee_id=(.*)&/,'&');
				
				$href = $href.replace(/&employee_id=(.*)/,'');
				
				window.location.href = $href +'&employee_id=' + $employee_id;
			}
				
			return false;
			
		}).on('submit', '#frmEmployeeCustomtime', function(e) {
			var $href = window.location.href;
			
			$.post($href, $('#frmEmployeeCustomtime').serialize()).done(function (data) {
				location.reload();
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
