var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var datepicker = ($.fn.datepicker !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			qs = "",
			$frmTimeCustom = $("#frmTimeCustom"),
			$frmTimeCustomImport = $("#frmTimeCustomImport");
		
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
		
		if ($frmTimeCustom.length > 0 && validate) {
			$frmTimeCustom.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmTimeCustomImport.length > 0 && validate) {
			$frmTimeCustomImport.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		$("#content").on("click", ".working_day", function () {
			var checked = $(this).is(":checked"),
				$tr = $(this).closest("tr");
			$tr.find("select").attr("disabled", checked);
		}).on("focusin", ".datepick", function () {
			if (datepicker) {
				var $this = $(this);
				$this.datepicker({
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev")
				});
			}
		});
		
		if ($("#grid").length > 0 && datagrid) {
			
			var m = window.location.href.match(/&type=(employee|calendar)&foreign_id=(\d+)/);
			if (m !== null) {
				qs = m[0];
			}
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminTime"+ $as_pf +"&action=pjActionUpdateCustom"+qs+"&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminTime"+ $as_pf +"&action=pjActionDeleteDate&id={:id}"}
				          ],
				columns: [{text: myLabel.time_date, type: "date", sortable: true, editable: false, width: 100, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				          {text: myLabel.time_start, type: "text", sortable: true, editable: false, width: 100},
				          {text: myLabel.time_end, type: "text", sortable: true, editable: false, width: 100},
				          {text: myLabel.time_lunch_start, type: "text", sortable: true, editable: false, width: 100},
				          {text: myLabel.time_lunch_end, type: "text", sortable: true, editable: false, width: 100},
				          {text: myLabel.time_dayoff, type: "select", sortable: true, editable: true, options: [
			     				       {label: myLabel.time_yesno.T, value: 'T'}, 
			     				       {label: myLabel.time_yesno.F, value: 'F'}
			     				       ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminTime&action=pjActionGetDate" + qs + $as_pf,
				dataType: "json",
				fields: ['date', 'start_time', 'end_time', 'start_lunch', 'end_lunch', 'is_dayoff'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminTime"+ $as_pf +"&action=pjActionDeleteDateBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminTime"+ $as_pf +"&action=pjActionSaveDate&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				"is_dayoff": ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminTime&action=pjActionGetDate" + qs + $as_pf, "date", "ASC", content.page, content.rowCount);
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
			obj.is_dayoff = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminTime&action=pjActionGetDate" + qs + $as_pf, "date", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);