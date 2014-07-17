var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var datagrid = ($.fn.datagrid !== undefined),
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
		
		function drawChart(items, title, tooltips) {
			if (google.visualization === undefined) {
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(__drawChart);
				return;
			}
			__drawChart(items, title, tooltips);
		}
		
		function __drawChart(items, title, tooltips) {
			var i, j, iCnt, jCnt, row,
				data = new google.visualization.DataTable(),

				options = {title: title, legend: {position: 'bottom'}, tooltip: {isHtml: true}},
				chart = new google.visualization.ColumnChart(document.getElementById('chart'));
			
			for (i = 0, iCnt = items[0].length; i < iCnt; i++) {
				if (i > 0) {
					data.addColumn('number', items[0][i]);
					if (tooltips !== undefined) {
						data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
					}
				} else {
					data.addColumn('string', items[0][i]);
				}
			}
			for (i = 1, iCnt = items.length; i < iCnt; i++) {
				row = [];
				for (j = 0, jCnt = items[i].length; j < jCnt; j++) { 
					row.push(items[i][j]);
					if (j > 0 && tooltips !== undefined) {
						row.push(['<ul class="google-visualization-tooltip-item-list">',
						          '<li class="google-visualization-tooltip-item">',
						          '<span style="font-family: Arial; color: rgb(51, 51, 51); margin: 0px; text-decoration: none; font-weight: bold;">', 
						          items[i][0], 
						          '</span></li>',
						          '<li class="google-visualization-tooltip-item" style=""><span style="font-family: Arial; color: rgb(51, 51, 51); margin: 0px; text-decoration: none;">',
						          items[0][j],
						          ':</span> ',
						          '<span style="font-family: Arial; color: rgb(51, 51, 51); margin: 0px; text-decoration: none; font-weight: bold;">',
						          tooltips[i][j],
						          '</span>',
						          '</li></ul>'
						          ].join(''));
					}
				}
				data.addRow(row);
			}
			
			chart.draw(data, options);
		}
		
		function formatBookingsTotal(str, obj) {
			return obj.total_amount_format;
		}
		function formatConfirmedTotal(str, obj) {
			return obj.confirmed_amount_format;
		}
		function formatPendingTotal(str, obj) {
			return obj.pending_amount_format;
		}
		function formatCancelledTotal(str, obj) {
			return obj.cancelled_amount_format;
		}

		function Employee() {
			
			this.cnt = {
				buttons: [],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 130},
				          {text: myLabel.total_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.confirmed_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.pending_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.cancelled_bookings, type: "text", sortable: true, editable: false, align: "center"}
				],
				dataUrl: "index.php?controller=pjAdminReports&action=pjActionGetEmployee" + $as_pf,
				dataType: "json",
				fields: ['name', 'total_bookings', 'confirmed_bookings', 'pending_bookings', 'cancelled_bookings'],
				paginator: {
					actions: [],
					gotoPage: false,
					paginate: false,
					total: true,
					rowCount: false
				}
			};
			
			this.amount = {
				buttons: [],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 130},
				          {text: myLabel.total_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatBookingsTotal},
				          {text: myLabel.confirmed_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatConfirmedTotal},
				          {text: myLabel.pending_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatPendingTotal},
				          {text: myLabel.cancelled_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatCancelledTotal}
				],
				dataUrl: "index.php?controller=pjAdminReports&action=pjActionGetEmployee" + $as_pf,
				dataType: "json",
				fields: ['name', 'total_amount', 'confirmed_amount', 'pending_amount', 'cancelled_amount'],
				paginator: {
					actions: [],
					gotoPage: false,
					paginate: false,
					total: true,
					rowCount: false
				}
			};
			
			return this;
		}
		
		function Service() {
			
			this.cnt = {
				buttons: [],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 130},
				          {text: myLabel.total_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.confirmed_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.pending_bookings, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.cancelled_bookings, type: "text", sortable: true, editable: false, align: "center"}
				],
				dataUrl: "index.php?controller=pjAdminReports&action=pjActionGetService" + $as_pf,
				dataType: "json",
				fields: ['name', 'total_bookings', 'confirmed_bookings', 'pending_bookings', 'cancelled_bookings'],
				paginator: {
					actions: [],
					gotoPage: false,
					paginate: false,
					total: true,
					rowCount: false
				}
			};
			
			this.amount = {
				buttons: [],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true, width: 130},
				          {text: myLabel.total_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatBookingsTotal},
				          {text: myLabel.confirmed_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatConfirmedTotal},
				          {text: myLabel.pending_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatPendingTotal},
				          {text: myLabel.cancelled_bookings, type: "text", sortable: true, editable: false, align: "right", renderer: formatCancelledTotal}
				],
				dataUrl: "index.php?controller=pjAdminReports&action=pjActionGetService" + $as_pf,
				dataType: "json",
				fields: ['name', 'total_amount', 'confirmed_amount', 'pending_amount', 'cancelled_amount'],
				paginator: {
					actions: [],
					gotoPage: false,
					paginate: false,
					total: true,
					rowCount: false
				}
			};
			
			return this;
		}
		
		$(document).on("focusin", ".datepick", function (e) {
			var $this = $(this);
			$this.datepicker({
				firstDay: $this.attr("rel"),
				dateFormat: $this.attr("rev")
			});
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		}).on("drawChart", ".frm-filter-advanced", function () {
			
			var $this = $(this),
				$chart = $("#chart"),
				$selected = $this.find("select[name='index']").find("option:selected"),
				index = $selected.val(),
				suffix = index == 'amount' ? 'amount' : 'bookings';
			
			if (index.length === 0) {
				$chart.hide();
			} else {
				var url,
					obj = {},
					content = $grid.datagrid("option", "content"),
					cache = $grid.datagrid("option", "cache");
				
				switch ($this.data("view")) {
				case 'services':
					url = 'index.php?controller=pjAdminReports&action=pjActionGetService' + $as_pf;
					break;
				case 'employees':
					url = 'index.php?controller=pjAdminReports&action=pjActionGetEmployee'+ $as_pf;
					break;
				}

				obj.page = 1;
				obj.rowCount = 100;
				$.extend(cache, obj);
				
				$.get(url, cache).done(function (data) {
					$chart.show();
					
					var items = [
					  ['Title'],
			          [myLabel.total_bookings],
			          [myLabel.confirmed_bookings],
			          [myLabel.pending_bookings],
			          [myLabel.cancelled_bookings]
					];
					
					var tooltips;
					if (index == 'amount') {
						tooltips = [[], [''], [''], [''], ['']];
					}
					
					for (var i = 0, iCnt = data.data.length; i < iCnt; i++) {
						items[0].push(data.data[i].name);
						items[1].push(parseFloat(data.data[i]['total_' + suffix]));
						items[2].push(parseFloat(data.data[i]['confirmed_' + suffix]));
						items[3].push(parseFloat(data.data[i]['pending_' + suffix]));
						items[4].push(parseFloat(data.data[i]['cancelled_' + suffix]));
						
						if (index == 'amount') {
							tooltips[1].push(data.data[i]['total_' + suffix + '_format']);
							tooltips[2].push(data.data[i]['confirmed_' + suffix + '_format']);
							tooltips[3].push(data.data[i]['pending_' + suffix + '_format']);
							tooltips[4].push(data.data[i]['cancelled_' + suffix + '_format']);
						}
					}
					
					drawChart.call(null, items, $selected.text(), tooltips);
				});
			}
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var url, nObj,
				obj = {},
				pattern = /^(.*)(\[)(\])$/,
				match = null,
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				match = arr[i].name.match(pattern);
				if (match === null) {
					obj[arr[i].name] = arr[i].value;
				} else {
					if (!obj.hasOwnProperty(match[1])) {
						obj[match[1]] = [];
					}
					obj[match[1]].push(arr[i].value);
				}
			}
			cache.q = "";
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			
			switch ($this.data("view")) {
			case 'services':
				url = 'index.php?controller=pjAdminReports&action=pjActionGetService' + $as_pf;
				nObj = new Service();
				break;
			case 'employees':
				url = 'index.php?controller=pjAdminReports&action=pjActionGetEmployee' + $as_pf;
				nObj = new Employee();
				break;
			}
			
			var new_columns = nObj[cache.index].columns,
				new_fields = nObj[cache.index].fields;
			
			$grid.datagrid("option", "columns", new_columns);
			$grid.datagrid("option", "fields", new_fields);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", url, "name", "ASC", content.page, content.rowCount);
			
			$this.trigger("drawChart");
			
			return false;
		});
		
		if ($("#grid_employees").length > 0 && datagrid) {
			var o = new Employee(),
				$grid = $("#grid_employees").datagrid(o.cnt);
			
			$(".frm-filter-advanced").trigger("drawChart");
		}
		
		if ($("#grid_services").length > 0 && datagrid) {
			var o = new Service(),
				$grid = $("#grid_services").datagrid(o.cnt);
			
			$(".frm-filter-advanced").trigger("drawChart");
		}
	});
})(jQuery_1_8_2);