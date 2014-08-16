(function ($) {
	$(function () {

		var $content = $("#content"),
			$frmUpdateBooking = $('#frmUpdateBooking'),
			$frmTemplate = $('#frmTemplate');
			$frmMenu = $('#frmMenu');
			$frmTablesGroup = $('#frmTablesGroup');
			$frmReminder = $(".frmReminder"),
			$tabs = $("#tabs"),
			$dialogDelete = $("#dialogDelete"),
			$dialogAvailability = $("#dialogAvailability"),
			$datepick = $(".datepick"),
			$calendar = $('#calendar-month');
			$monthly = $('#monthly-review');
			dOpts = {};
			$frmAddbooking = $('body').find('#frmAddbooking'),
			$tooltip = $('body').find('.customer-name .notes'),
			$code_graph = $('body').find(' #code-graph');
			
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
			
		if ($frmAddbooking.length > 0) {
			$frmAddbooking.validate();
		}
			
		if ( $frmTablesGroup.length > 0) {
			$frmTablesGroup.validate();
		}
		
		if ($tooltip.length > 0) {
			$tooltip.tooltip();
		}
			
		if ($code_graph.length > 0) {
			eval($('#code-graph').text());
			prettyPrint();
		}
		 
		if ($calendar.length >0 ) {
			
			$.get("index.php?controller=pjAdminBookings&action=calendar" + $rbpf).done(function (data) {
				$calendar.html(data);
				
			});
		}
		
		if ($monthly.length >0 ) {
			
			$.get("index.php?controller=pjAdminBookings&action=monthly" + $rbpf).done(function (data) {
				$monthly.html(data);
				
			});
		}
		
		$('body').on('click', '.calendar-control', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}

			$url = $(this).attr('href');
			
			$.get($url).done(function (data) {
				$calendar.html(data);
			});
				
			return false;
		}).on('click', '.monthly-control', function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			$m = $(this).attr('data-m');
			
			if ( typeof $m === "undefined" ) 
				$month = '';
			
			else $month = '&m=' + $m;
			
			$.get("index.php?controller=pjAdminBookings&action=monthly" + $rbpf + $month).done(function (data) {
				$monthly.html('');
				$monthly.html(data);
				
			});
			
		});
		
		if ($frmTemplate.length > 0) {
			$frmTemplate.validate();
		}
		
		if ($frmMenu.length > 0) {
			$frmMenu.validate();
		}
		
		$('body').on('click.modal.data-api', '[data-toggle="modal"]', function ( e ) {
			
			updateid = $(this).attr('href');
			
			if (updateid == '#updatebooking') {
				
				$bookingid = $(this).attr('data-id');
				$customer = $(this).attr('data-customer');
				
				$url = "index.php?controller=pjAdminBookings&action=update" + $rbpf;
				
				if ( typeof $bookingid != "undefined" ){
					$url +=  "&id=" + $bookingid;
				}
				
				if ( typeof $customer != "undefined" ){
					$url +=  "&customer=" + $customer;
				}
				
				$container = $(updateid + ' .modal-body');
				
				if ( !$(updateid).hasClass('in') ) {
					$container.html('<p>Loading...</p>');
				}
				
				$.get( $url ).done(function (data) {
					
					$container.html($(data).find("#frmUpdateBooking"));
					$('body').append($(data).find('#dialogAvailability'));
					
					$frmUpdateBooking = $('body').find('#frmUpdateBooking');
					
					if ($frmUpdateBooking.length > 0) {
						$frmUpdateBooking.validate();
					}
					
					
				});
				
			} else if ( updateid == '#editCustomTime' ) {
				
				$custom_id = $(this).attr('data-id');
				
				$url = "index.php?controller=pjAdminTime&action=update" + $rbpf;
				
				if ( typeof $custom_id != "undefined" ){
					$url +=  "&id=" + $custom_id;
				}
				
				$container = $(updateid + ' .modal-body');
				
				if ( !$(updateid).hasClass('in') ) {
					$container.html('<p>Loading...</p>');
				}
				
				$.get( $url ).done(function (data) {
					
					$container.html($(data).find("#frmTimeCustom"));
				});
				
			} else {
				
				$frmAddbooking = $('body').find('#frmAddbooking');
				
				if ($frmAddbooking.length > 0) {
					$frmAddbooking.validate();
					$frmAddbooking.find('.rbBooking-date').val($(this).attr('data-date')) ;
					$frmAddbooking.find('.rbBooking-hour').val($(this).attr('data-hour')) ;
					$frmAddbooking.find('.rbBooking-tableid').val($(this).attr('data-tableid')) ;
					
					$seats = $(this).attr('data-table-seats');
					$minimum = $(this).attr('data-table-minimum');
					
					var $select_option = '', $i = 0;
					if ($minimum > 0 && $minimum <= $seats ) {
						
						for ( $i = $minimum; $i <= $seats; $i++ ) {
							$select_option += '<option value="' + $i + '">' + $i + '</option>';
						}
						
					} else {
						$select_option += '<option value="">--</option>';
					}
					
					$frmAddbooking.find('#rb_people').html($select_option);
				}
			}
		});
		
		
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
			
			//$dialogDelete.data('id', $(this).attr("rel")).dialog('open');
			$dialogDelete.data('data', $(this).data()).dialog('open');
			
			return false;
		}).delegate(".datepick", "focusin", function (e) {
			switch ($(this).attr("id")) {
				case "schedule_date":
					dOpts = $.extend(dOpts, {
						onSelect: function (dateText, inst) {
							getSchedule.apply(null, [dateText]);
						}
					});
					break;
				case "paper_date":
					dOpts = $.extend(dOpts, {
						onSelect: function (dateText, inst) {
							getPaper.apply(null, [dateText]);
						}
					});
					break;
			}
			$(this).datepicker(dOpts);
		}).delegate("#payment_method", "change", function () {
			switch ($("option:selected", this).val()) {
				case 'creditcard':
					$(".boxCC").show();
					break;
				default:
					$(".boxCC").hide();
			}
		}).delegate("#status", "change", function () {
			switch ($("option:selected", this).val()) {
				case 'enquiry':
					$(".billing").hide();
					break;
				default:
					$(".billing").show();
			}
		}).delegate(".btnAddTable", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.post("index.php?controller=pjAdminBookings&action=getTables" + $rbpf, $frmUpdateBooking.serialize()).done(function (data) {
				$("tbody", $("#tblBookingTables")).append(data);
			});
			return false;
			
		}).delegate(".btnAddTableGroup", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.post("index.php?controller=pjAdminBookings&action=getTables&tablesgroup=1" + $rbpf, $frmUpdateBooking.serialize()).done(function (data) {
				$("tbody", $("#tblBookingTablesGroup")).append(data);
			});
			return false;
		}).delegate(".btnRemoveTable", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).parent().parent().remove();
			return false;
		}).delegate("select[name='table_id[]']", "change", function () {
			var $this = $(this),
				val = $("option:selected", this).val();
			$this.parent().next().html(val.split("|")[1]);
			$this.parent().next().next().html(val.split("|")[2]);
		}).delegate("td.meta", "click", function () {
			var meta = $(this).parent().metadata();
			if (meta.id) {
				window.location.href = "index.php?controller=pjAdminBookings&action=update&id=" + meta.id + $rbpf;
			}
		}).delegate(".availability", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			$('body').find("#dialogAvailability").dialog("open");
			return false;
		});
		
		if ($frmUpdateBooking.length > 0) {
			$frmUpdateBooking.validate();
			//$("#status, #is_paid").buttonset();
		}
		
		if ($frmReminder.length > 0) {
			$frmReminder.validate();
		}
		
		if ( $('.sub-tabs').length) {
			$('.sub-tabs').tabs();
		}
		
		if ($tabs.length > 0) {
			var tOpt = {
				select: function (event, ui) {
					
					switch (ui.index) {
						case 0:
							window.location.href = "index.php?controller=pjAdminBookings&action=schedule" + $rbpf;
							break;
						case 3:
							window.location.href = "index.php?controller=pjAdminBookings&action=paper" + $rbpf;
							break;
						case 4:
							window.location.href = "index.php?controller=pjAdminBookings&action=customer" + $rbpf;
							break;
						case 5:
							window.location.href = "index.php?controller=pjAdminBookings&action=statistics" + $rbpf;
							break;
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
		}
		
		if ($dialogDelete.length > 0) {
			$dialogDelete.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				buttons: {
					'Delete': function() {
						
						var $template = $(this).data('data').template,
							$menu = $(this).data('data').menu,
							$tables_group = $(this).data('data').tables_group,
							$option = {};
						
						if ( typeof $(this).data('data').template != "undefined" ) {
							$option = {
								id: $(this).data('data').id,
								template: $(this).data('data').template
							};
						} else if ( typeof $(this).data('data').menu != "undefined" ) {
							$option = {
									id: $(this).data('data').id,
									menu: $(this).data('data').menu
								};
						} else if ( typeof $(this).data('data').tables_group != "undefined" ) {
							$option = {
									id: $(this).data('data').id,
									tables_group: $(this).data('data').tables_group
								};
						} else {
							$option = {
								id: $(this).data('data').id
							};
						}
						
						
						$.post("index.php?controller=pjAdminBookings&action=delete" + $rbpf, $option).done(function (data) {
							
							if ( typeof $template != "undefined" || typeof $menu != "undefined" || typeof $tables_group != "undefined") { 
								location.reload();
							} else {
								$content.html(data);
							}
							
							$("#tabs").tabs();
							
							$('.sub-tabs').tabs();
							
							$("#tabs").tabs("option", "selected", 6 );
							
						});
						
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
		
		function getSchedule(dateText) {
			$.get("index.php?controller=pjAdminBookings&action=getSchedule" + $rbpf, {
				date: dateText
			}).done(function (data) {
				$("#boxSchedule").html(data);
				$("#box-date-message").html($(data).find("#date-message").html());

				$tooltip = $('body').find('.customer-name .notes');

				if ($tooltip.length > 0) {
					$tooltip.tooltip();
				}
				
			});
		}
		
		function getPaper(dateText) {
			$.get("index.php?controller=pjAdminBookings&action=getPaper" + $rbpf, {
				date: dateText
			}).done(function (data) {
				$("#boxPaper").html(data);
			});
		}
		
		if ($dialogAvailability.length > 0) {
			$dialogAvailability.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				width: 800,
				open: function () {
					var $this = $(this);
					$.get("index.php?controller=pjAdminBookings&action=getAvailability" + $rbpf, {
						"id": $frmUpdateBooking.find("input[name='id']").val(),
						"date": $frmUpdateBooking.find("#date").val(),
						"hour": $frmUpdateBooking.find("#hour option:selected").val(),
						"minute": $frmUpdateBooking.find("#minute option:selected").val()
					}).done(function (data) {
						$dialogAvailability.html(data);
					});
				},
				buttons: {
					'Close': function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
	});
})(window.jQuery);