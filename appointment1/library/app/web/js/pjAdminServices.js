var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateService = $("#frmCreateService"),
			$frmUpdateService = $("#frmUpdateService"),
			multiselect = ($.fn.multiselect !== undefined),
			spinner = ($.fn.spinner !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			qs = "",
			tipsy = ($.fn.tipsy !== undefined);
		
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
		
		if (tipsy) {
			$(".listing-tip").tipsy({
				offset: 1,
				opacity: 1,
				html: true,
				gravity: "nw",
				className: "tipsy-listing"
			});
		}
		
		if ($frmCreateService.length > 0) {
			$frmCreateService.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		if ($frmUpdateService.length > 0) {
			$frmUpdateService.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if (multiselect) {
			$("select[name^='employee_id']").multiselect({
				show: ['fade', 250],
				hide: ['fade', 250]
			});
			
			$("select[name^='resources_id']").multiselect({
				show: ['fade', 250],
				hide: ['fade', 250]
			});
			
			$("select[name^='extra_id']").multiselect({
				show: ['fade', 250],
				hide: ['fade', 250]
			});
		}
		
		if (spinner) {
			$(".spinner").spinner({
				"min": 0,
				"step": 1,
				stop: function(event, ui) {
					var total = 0,
						len_gth = parseInt($("#length").val(), 10),
						before = parseInt($("#before").val(), 10),
						after = parseInt($("#after").val(), 10);
					if (!isNaN(len_gth)) {
						total += len_gth;
					}
					if (!isNaN(before)) {
						total += before;
					}
					if (!isNaN(after)) {
						total += after;
					}
					$("#total").val(total);
					$("#print_total").text(total);
				}
			});
		}
		
		function formatPrice(str, obj) {
			return obj.price_format;
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var m = window.location.href.match(/&type=(service|calendar)&foreign_id=(\d+)/);
			
			if (m !== null) {
				qs = m[0];
				
				var options = {
					buttons: [{type: "edit", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionUpdateCustomTime" + qs + "&id={:id}"},
					          {type: "delete", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceCustomTime&id={:id}"}
					          ],
					columns: [{text: myLabel.price, type: "text", sortable: true, editable: true, renderer: formatPrice, align: "right", width: 65, editableWidth: 60},
					          {text: myLabel.len, type: "spinner", min: 0, step: 1, sortable: true, editable: true, editableWidth: 50, align: "center"},
					          {text: myLabel.before, type: "spinner", min: 0, step: 1, sortable: true, editable: true, editableWidth: 50, align: "center"},
					          {text: myLabel.after, type: "spinner", min: 0, step: 1, sortable: true, editable: true, editableWidth: 50, align: "center"},
					          {text: myLabel.total, type: "text", sortable: true, editable: false, align: "center"},
					          ],
							  
					dataUrl: "index.php?controller=pjAdminServices&action=pjActionGetService" + qs + $as_pf,
					dataType: "json",
					fields: ['price', 'length', 'before', 'after', 'total'],
					paginator: {
						actions: [
						   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceBulkCustomTime", render: true, confirmation: myLabel.delete_confirmation}
						],
						gotoPage: true,
						paginate: true,
						total: true,
						rowCount: true
					},
					saveUrl: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionSaveServiceCustomTime&id={:id}",
					select: {
						field: "id",
						name: "record[]"
					}
				};
				
			} else if ( window.location.href.match(/&action=pjActionCategory/) !== null ) {
				
				var options = {
						buttons: [{type: "edit", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionUpdateCategory" + "&id={:id}"},
						          {type: "delete", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceCategory&id={:id}"}
						          ],
						columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 200},
						          {text: myLabel.show_front, type: "text", sortable: true, editable: true, width: 70},
						          {text: myLabel.message, type: "text", sortable: true, editable: true, width: 340},
						          ],
								  
						dataUrl: "index.php?controller=pjAdminServices&action=pjActionGetService&type=category" + $as_pf,
						dataType: "json",
						fields: ['name', 'show_front', 'message'],
						paginator: {
							actions: [
							   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceBulkCategory", render: true, confirmation: myLabel.delete_confirmation}
							],
							gotoPage: true,
							paginate: true,
							total: true,
							rowCount: true
						},
						saveUrl: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionSaveServiceCategory&id={:id}",
						select: {
							field: "id",
							name: "record[]"
						}
					};
			
			} else if ( window.location.href.match(/&action=pjActionExtraService/) !== null ) {
				
				var options = {
						buttons: [{type: "edit", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionExtraService" + "&id={:id}"},
						          {type: "delete", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteExtraService&id={:id}"}
						          ],
						columns: [{text: myLabel.name, type: "text", sortable: true, editable: true},
						          {text: myLabel.price, type: "text", sortable: true, editable: true},
						          {text: myLabel.length, type: "text", sortable: true, editable: true},
						          {text: myLabel.message, type: "text", sortable: true, editable: true},
						          ],
								  
						dataUrl: "index.php?controller=pjAdminServices&action=pjActionGetService&type=extraservice" + $as_pf,
						dataType: "json",
						fields: ['name', 'price', 'length', 'message'],
						paginator: {
							actions: [
							   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteExtraServiceBulk", render: true, confirmation: myLabel.delete_confirmation}
							],
							gotoPage: true,
							paginate: true,
							total: true,
							rowCount: true
						},
						saveUrl: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionSaveExtraService&id={:id}",
						select: {
							field: "id",
							name: "record[]"
						}
					};
			
			} else if ( window.location.href.match(/&action=pjActionResources/) !== null ) {
				
				var options = {
						buttons: [{type: "edit", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionUpdateResources" + "&id={:id}"},
						          {type: "delete", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteResources&id={:id}"}
						          ],
						columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 200},
						          {text: myLabel.message, type: "text", sortable: true, editable: true, width: 340},
						          ],
								  
						dataUrl: "index.php?controller=pjAdminServices&action=pjActionGetService&type=resources" + $as_pf,
						dataType: "json",
						fields: ['name', 'message'],
						paginator: {
							actions: [
							   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceBulkResources", render: true, confirmation: myLabel.delete_confirmation}
							],
							gotoPage: true,
							paginate: true,
							total: true,
							rowCount: true
						},
						saveUrl: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionSaveResources&id={:id}",
						select: {
							field: "id",
							name: "record[]"
						}
					};
				
			} else {
			
				var options = {
					buttons: [{type: "edit", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionUpdate&id={:id}"},
					          {type: "delete", url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteService&id={:id}"}
					          ],
					columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 130},
					          {text: myLabel.employees, type: "text", sortable: true, editable: false, align: "center"},
					          {text: myLabel.price, type: "text", sortable: true, editable: true, renderer: formatPrice, align: "right", width: 65, editableWidth: 60},
					          {text: myLabel.len, type: "spinner", min: 0, step: 1, sortable: true, editable: true, editableWidth: 50, align: "center"},
					          {text: myLabel.total, type: "text", sortable: true, editable: false, align: "center"},
					          {text: myLabel.category, type: "text", sortable: true, editable: false, align: "center"},
					          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [
							                                                                                     {label: myLabel.active, value: 1}, 
							                                                                                     {label: myLabel.inactive, value: 0}
							                                                                                     ], applyClass: "pj-status"}],
					dataUrl: "index.php?controller=pjAdminServices&action=pjActionGetService" + $as_pf,
					dataType: "json",
					fields: ['name', 'employees', 'price', 'length', 'total', 'category', 'is_active'],
					paginator: {
						actions: [
						   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionDeleteServiceBulk", render: true, confirmation: myLabel.delete_confirmation}
						],
						gotoPage: true,
						paginate: true,
						total: true,
						rowCount: true
					},
					saveUrl: "index.php?controller=pjAdminServices"+ $as_pf +"&action=pjActionSaveService&id={:id}",
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
		
		$(document).on("click", ".btn-all", function (e) {
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
			$grid.datagrid("load", "index.php?controller=pjAdminServices&action=pjActionGetService" + $as_pf, "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminServices&action=pjActionGetService" + $as_pf, "name", "ASC", content.page, content.rowCount);
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
			$grid.datagrid("load", "index.php?controller=pjAdminServices&action=pjActionGetService" + $as_pf, "id", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);