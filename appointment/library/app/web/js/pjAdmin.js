(function ($, undefined) {
	$(function () {
		var $frmLoginAdmin = $("#frmLoginAdmin"),
			$frmForgotAdmin = $("#frmForgotAdmin"),
			$frmUpdateProfile = $("#frmUpdateProfile"),
			$frmBooking = '',
			$frmFreetime = '',
			$frmExtraService = '',
			$dialogDeleteAvatar = $("#dialogDeleteAvatar"),
			$dialogAddBooking = $("#dialogAddBooking"),
			$dialogEditBooking = $("#dialogEditBooking"),
			$dialogView = $("#dialogView"),
			$dialogFreetime = $('#dialogFreetime'),
			$dialogAddCustomer = $("#dialogAddCustomer"),
			$dialogItemDelete = $('#dialogItemDelete'),
			$dialogEditTimeBooking = $('#dialogEditTimeBooking'),
			$dialogEditbookingStatus = $('#dialogEditbookingStatus'),
			$dialogRemoveFreetime = $('#dialogRemoveFreetime'),
			$dialogExtraService = $('#dialogExtraService'),
			$tabs = $("#tabs"),
			multiselect = ($.fn.multiselect !== undefined),
			dialog = ($.fn.dialog !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			validate = ($.fn.validate !== undefined),
			$validate;
		
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
		
		if ($tabs.length > 0 && tabs) {
			$tabs.tabs();
		}
		
		$("body").on("click", "a.dashboardView", function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogView.length > 0 && dialog) {
				$dialogView.data("data", $(this).data()).dialog("open");;
			}
			return false;
			
		}).on("click", "a.editTimeBooking", function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogEditTimeBooking.length > 0 && dialog) {
				$dialogEditTimeBooking.data("data", $(this).data()).dialog("open");;
			}
			return false;
			
		}).on("click", "a.editbooking", function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogEditBooking.length > 0 && dialog) {
				$dialogEditBooking.data("data", $(this).data()).dialog("open");;
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
		}).on("click", "a.removeFreetime", function (e) {
			
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			if ($dialogRemoveFreetime.length > 0 && dialog) {
				$dialogRemoveFreetime.data("data", $(this).data()).dialog("open");;
			}
			
			return false;
		});
		
		$(document).on("click.as", ".asSlotAvailable", function (e) {	
			
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
		
		/* Remove Freetime */
		if ($dialogRemoveFreetime.length > 0 && dialog) {
			$dialogRemoveFreetime.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				buttons: {
					"Delete": function () {
						var $this = $(this);
						$this.dialog("close");
						
						$.get("index.php?controller=pjAdmin&action=pjActionRemoveFreetime&freetime_id=" + $this.data("data").freetime_id + $as_pf).done(function (data) {
							location.reload();
						});
					},
					"Cancel": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		if ($dialogView.length > 0 && dialog) {
			$dialogView.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 300,
				open: function () {
					
					var $booking_id = $(this).data("data").booking_id,
						$extra_count = $(this).data("data").extra_count;
					
					if ($dialogView.length > 0) {
						$html = '';
						
						$html += '<table style="width: 100%;" class="pj-table"><tbody>';
						
						if ( typeof $booking_id == 'undefined' ) {
							$html += '<tr class="pj-table-row-even">';
							$html += '<td style="text-align: center;"><input type="radio" value="1" name="radio_view" id="view_1"></td>';
							$html += '<td>Lisää vapaa</td>';
							$html += '</tr>';
							
							$html += '<tr class="pj-table-row-old">';
							$html += '<td style="text-align: center;"><input type="radio" checked="checked" value="2" name="radio_view"></td>';
							$html += '<td>Tee varaus</td>';
							$html += '</tr>';
							
						} else {
							$html += '<tr class="pj-table-row-even">';
							$html += '<td style="text-align: center;"><input type="radio" checked="checked" value="3" name="radio_view"></td>';
							$html += '<td>Muokkaa kestoa</td>';
							$html += '</tr>';
							
							$html += '<tr class="pj-table-row-old">';
							$html += '<td style="text-align: center;"><input type="radio" value="4" name="radio_view"></td>';
							$html += '<td> Muokkaa tilaa</td>';
							$html += '</tr>';
							
							if ( typeof $extra_count != 'undefined' && $extra_count > 0 ) {
								$html += '<tr class="pj-table-row-even">';
								$html += '<td style="text-align: center;"><input type="radio" value="5" name="radio_view"></td>';
								$html += '<td>Lisää lisäpalvelu</td>';
								$html += '</tr>';
							}
						}
						
						$html += '</tbody></table>';
						
						$dialogView.html($html);
					}
				},
				buttons: {
					"Jatka": function () {
						$(this).dialog("close");
						var $val = $dialogView.find( "input[name=radio_view]:radio:checked" ).val();
						
						if ( $val == 1 ) {
							
							if ($dialogFreetime.length > 0 && dialog) {
								$dialogFreetime.data("data", $(this).data('data')).dialog("open");;
							}
							
						} else if ( $val == 2 ) {
							
							if ($dialogAddBooking.length > 0 && dialog) {
								$dialogAddBooking.data("data", $(this).data('data')).dialog("open");;
							}
							
						} else if ( $val == 3 ) {
							if ($dialogEditTimeBooking.length > 0 && dialog) {
								$dialogEditTimeBooking.data("data", $(this).data('data')).dialog("open");;
							}
							
						} else if ( $val == 4 ) {
							if ($dialogEditbookingStatus.length > 0 && dialog) {
								$dialogEditbookingStatus.data("data", $(this).data('data')).dialog("open");;
							}
						} else if ( $val == 5 ) {
							if ($dialogExtraService.length > 0 && dialog) {
								$dialogExtraService.data("data", $(this).data('data')).dialog("open");;
							}
						}
						
						return false;
					},
					"Peruuta": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		/* Extra Service */
		if ($dialogExtraService.length > 0 && dialog) {
			$dialogExtraService.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 255,
				open: function () {
					$dialogExtraService.html("");
					$booking_id = $(this).data('data').booking_id;
					$service_id = $(this).data('data').service_id;
					$booking_date = $(this).data('data').booking_date;
					
					$.get("index.php?controller=pjAdmin&action=getExtraService" + $as_pf, {
						"booking_id": $booking_id,
						"service_id": $service_id,
						"booking_date": $booking_date,
					}).done(function (data) {
						$dialogExtraService.html(data);
						
						$frmExtraService = $('body').find('#frmExtraService');
						
						if (multiselect) {
							$("body").find("select[name^='extra_id']").multiselect({
								show: ['fade', 250],
								hide: ['fade', 250]
							});
						}
						
					});
				},
				buttons: {
					"Jatka": function () {
						$(this).dialog("close");
						
						$.post("index.php?controller=pjAdmin&action=getExtraService" + $as_pf, $frmExtraService.serialize()).done(function (data) {
							location.reload();
						});
						
						return false;
					},
					"Peruuta": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		/* Edit Time */
		if ($dialogEditTimeBooking.length > 0 && dialog) {
			$dialogEditTimeBooking.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 190,
				open: function () {
					$dialogEditTimeBooking.html("");
					$booking_id = $(this).data('data').booking_id;
					
					$.get("index.php?controller=pjAdmin&action=pjActionEditTime&booking_id=" + $booking_id + $as_pf).done(function (data) {
						$dialogEditTimeBooking.html(data);
					});
				},
				buttons: {
					"Jatka": function () {
						$(this).dialog("close");
						var $val = $dialogEditTimeBooking.find( "select[name=edittime]" ).val();
						
						$.get("index.php?controller=pjAdmin&action=pjActionEditTime" + $as_pf, {
							"booking_id": $booking_id,
							"time": $val
						}).done(function (data) {
							location.reload();
						});
						
						return false;
					},
					"Peruuta": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		/* Edit Status */
		if ($dialogEditbookingStatus.length > 0 && dialog) {
			$dialogEditbookingStatus.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 190,
				open: function () {
					$dialogEditbookingStatus.html("");
					$booking_id = $(this).data('data').booking_id;
					$booking_status = $(this).data('data').booking_status;
					
					var $status = [];
					
					$status['cancelled'] = 'Peruttu';
					$status['pending'] = 'Odottaa';
					$status['confirmed'] = 'Vahvistettu';
					$status['arrived'] = 'Asiakas saapui';
					$status['paid'] = 'Asiakas maksoi';
					$status['no_show_up'] = "Asiakas ei ilmestynyt paikalle";
					
					$html = '';
					$html += '<form action="" method="post" id="frmEditStatus" class="form pj-form">';
					$html += '<input type="hidden" name="booking_id" value="'+ $booking_id +'">';
					$html += '<p>';
					$html += '<select id="editstatus" name="editstatus" class="pj-form-field w150">';
					
					for ( $k in $status ) {
						if ( $k == $booking_status ) 
							$selected = 'selected="selected" ';
						else $selected = '';
						
						$html += '<option '+ $selected +'value="'+ $k +'">'+ $status[$k] +'</option>';
					}
					
					$html += '</select>';
					$html += '</p>';
					$html += '</form>';
					
					$dialogEditbookingStatus.html($html);
				},
				buttons: {
					"Jatka": function () {
						$(this).dialog("close");
						var $val = $dialogEditbookingStatus.find( "select[name=editstatus]" ).val();
						
						$.get("index.php?controller=pjAdmin&action=pjActionEditStatus" + $as_pf, {
							"booking_id": $booking_id,
							"status": $val
						}).done(function (data) {
							location.reload();
						});
						
						return false;
					},
					"Peruuta": function () {
						$(this).dialog("close");
					}
				}
			});
		}
		
		/* Freetime */
		if ($dialogFreetime.length > 0 && dialog) {
			$dialogFreetime.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 500,
				open: function () {
					$this = $(this);
					$dialogFreetime.html("");
					$employee_id = $(this).data('data').employee_id;
					$start_ts = $(this).data('data').start_ts;
					
					$.get("index.php?controller=pjAdminEmployees&action=pjActionFreetime&pjAdmin=1" + $as_pf,  {
						"employee_id": $employee_id,
						"start_ts": $start_ts
					}).done(function (data) {
						
						$dialogFreetime.html($(data).find('#frmFreetime'));
						
						$dialogFreetime.on("change", "select[name='employee_id']", onChangeEmployee);
						
						$frmFreetime = $('body').find('#frmFreetime');
						
						$('body').on('submit', '#frmFreetime', function (e) {
							
							$.post("index.php?controller=pjAdminEmployees&action=pjActionFreetime&pjAdmin=1" + $as_pf, $frmFreetime.serialize()).done(function (data) {
								location.reload();
							});
							
							$this.dialog("close");
							
							return false;
						});
						
					});
				}
			});
		}
		
		/* Create Booking */
		if ($dialogAddBooking.length > 0 && dialog) {
			$dialogAddBooking.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 700,
				open: function () {
					$this = $(this);
					$dialogAddBooking.html("");
					$employee_id = $(this).data('data').employee_id;
					$start_ts = $(this).data('data').start_ts;
					
					$.get("index.php?controller=pjAdminBookings&action=pjActionCreate&pjAdmin=1" + $as_pf,  {
						"employee_id": $employee_id,
						"start_ts": $start_ts
					}).done(function (data) {
						$dialogAddBooking.html($(data).find('#frmCreateBooking'));
						$dialogAddBooking.dialog("option", "position", "center");
						
						$tabs = $("#tabs");
						$frmCreateBooking = $('body').find('#frmCreateBooking');
						
						if ($tabs.length > 0 && tabs) {
							$tabs.tabs();
						}
						
						$("body").on("click", ".notice-close", function (e) {
							if (e && e.preventDefault) {
								e.preventDefault();
							}
							$('body').find('.notice-box').fadeOut();
							return false;
						});
						
						/* Validate */
						if ($frmCreateBooking.length > 0 && validate) {
							
							$validate = $frmCreateBooking.validate({
								errorPlacement: function (error, element) {
									error.insertAfter(element.parent());
								},
								onkeyup: false,
								errorClass: "err",
								wrapper: "em",
							});
							$frmBooking = $frmCreateBooking;
						} /* End Validate */
						
						$frmBooking.submit( function (e) {
							if ( $validate.form() && $("#boxBookingItems").find("table").length > 0 ) {
								$.post("index.php?controller=pjAdminBookings&action=pjActionCreate&pjAdmin=1" + $as_pf, $frmBooking.serialize()).done(function (data) {
									location.reload();
									
								});
							}
							return false;
						});
						
						/* Add Customer */
						addCustomer();
						
						/* Add Services */
						addservice();
						
						dialogItemDelete();
					});
				},
				close: function() {
					location.reload();
				}
			});
		}
		
		/* Update Booking */
		if ($dialogEditBooking.length > 0 && dialog) {
			$dialogEditBooking.dialog({
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: false,
				width: 800,
				open: function () {
					$this = $(this);
					$dialogEditBooking.html("");
					$booking_id = $(this).data('data').booking_id;
					$employee_id = $(this).data('data').employee_id;
					$start_ts = $(this).data('data').start_ts;
					
					$.get("index.php?controller=pjAdminBookings&action=pjActionUpdate&pjAdmin=1" + $as_pf,  {
						"id" : $booking_id,
						"employee_id": $employee_id,
						"start_ts": $start_ts
					}).done(function (data) {
						$dialogEditBooking.html($(data).find('#frmUpdateBooking'));
						$dialogEditBooking.dialog("option", "position", "center");
						
						$tabs = $("#tabs");
						$frmUpdateBooking = $('body').find('#frmUpdateBooking');
						
						if ($tabs.length > 0 && tabs) {
							$tabs.tabs();
						}
						
						$("body").on("click", ".notice-close", function (e) {
							if (e && e.preventDefault) {
								e.preventDefault();
							}
							$('body').find('.notice-box').fadeOut();
							return false;
						});
						
						/* Validate */
						if ($frmUpdateBooking.length > 0 && validate) {
							
							$validate = $frmUpdateBooking.validate({
								errorPlacement: function (error, element) {
									error.insertAfter(element.parent());
								},
								onkeyup: false,
								errorClass: "err",
								wrapper: "em",
							});
							$frmBooking = $frmUpdateBooking;
						} /* End Validate */
						
						$frmBooking.submit( function (e) {
							if ($validate.form() && $("#boxBookingItems").find("table").length > 0 ) {
								$.post("index.php?controller=pjAdminBookings&action=pjActionUpdate&pjAdmin=1" + $as_pf, $frmBooking.serialize()).done(function (data) {
									location.reload();
								});
								
								//$this.dialog("close");
							}
							
							return false;
						});
						
						/* Add Customer */
						addCustomer();
						
						getBookingItems.call(null, $frmBooking.find("input[name='id']").val(), $frmBooking.find("input[name='tmp_hash']").val());
						
						/* Add Services */
						addservice();
						
						dialogItemDelete();
					});
				},
				close: function() {
					location.reload();
				}
			});
		}
		
		function dialogItemDelete () {
			
			$dialogItemDelete = $('body').find('#dialogItemDelete');
			
			$('body').on("click", ".item-delete", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				if ($dialogItemDelete.length > 0 && dialog) {
					$dialogItemDelete.data("id", $(this).data("id")).dialog("open");
				}
				return false;
			});
			
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
		}
		
		if ($frmLoginAdmin.length > 0 && validate) {
			$frmLoginAdmin.validate({
				rules: {
					login_email: {
						required: true,
						email: true
					},
					login_password: "required"
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmForgotAdmin.length > 0 && validate) {
			$frmForgotAdmin.validate({
				rules: {
					forgot_email: {
						required: true,
						email: true
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em"
			});
		}
		
		if ($frmUpdateProfile.length > 0 && validate) {
			$frmUpdateProfile.validate({
				rules: {
					"email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdminEmployees&action=pjActionCheckEmail" + $as_pf
					},
					"password": "required",
					"phone": "required"
				},
				messages: {
					"email": {
						remote: myLabel.email_taken
					}
				},
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
			
			$("select[name^='extra_id']").multiselect({
				show: ['fade', 250],
				hide: ['fade', 250]
			});
		}
		
		$("#content").on("click", ".btnDeleteAvatar", function () {
			if ($dialogDeleteAvatar.length > 0 && dialog) {
				$dialogDeleteAvatar.dialog("open");
			}
		});
		
		if ($dialogDeleteAvatar.length > 0 && dialog) {
			$dialogDeleteAvatar.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: {
					"Yes": function () {
						$.post("index.php?controller=pjAdminEmployees&action=pjActionDeleteAvatar" + $as_pf).done(function(data) {
							
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
		
		/* Add Services */
		function addservice() {
			$serviceItemAdd = $('body').find('#serviceItemAdd');
			
			if ($serviceItemAdd.length > 0) {
				
				$serviceItemAdd.html("");
				$.get("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf + "&employee_id=" + $employee_id + "&start_ts=" + $start_ts, $frmBooking.find("input[name='id'], input[name='tmp_hash'], #date_from, #hour_from, #minute_from, #date_to, #hour_to, #minute_to").serialize()).done(function (data) {
					$serviceItemAdd.html($(data).html());
					onChangeService();
				});
				
				$('body').on('click', '.serviceAdd', function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}

					if ($("#boxBookingItems").find("table").length == 0) {
						$.post("index.php?controller=pjAdminBookings&action=pjActionItemAdd" + $as_pf, $('body').find("form").serialize()).done(function (data) {
							getBookingItems.call(null, $frmBooking.find("input[name='id']").val(), $frmBooking.find("input[name='tmp_hash']").val());
							
						});
					}
					
				}).on("change", "select[name='category_id']", function (e) {
					
					var $el = $(this),
						$form = $el.closest("form"),
						category_id = $form.find("select[name='category_id']").find("option:selected").val();
					
					$form.find("#bookingServices").html("");
					$form.find("#bookingServicesTime").html("");
					
					$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, {
						"category_id": category_id,
						"employee_id": $employee_id,
						"start_ts": $start_ts
						
					}).done(function (data) {
						$form.find("#bookingServices").html( $(data).find(".bookingServices").html() );
						$form.find("#bookingServicesTime").html( $(data).find(".bookingServicesTime").html() ).show();
						
						onChangeService();
					});
				
				}).on("change", "select[name='servicetime_id']", function (e) {
					var 
						$form = $('body').find('#serviceItemAdd'),
						$start_ts = $form.find("select[name='servicetime_id']").find("option:selected").attr('data-start_ts'),
						$end_ts = $form.find("select[name='servicetime_id']").find("option:selected").attr('data-end_ts'),
						$end_label = $form.find("select[name='servicetime_id']").find("option:selected").attr('data-end'),
						selector = "input[name='employee_id'], input[name='start_ts'], input[name='end_ts']";
					
						$form.find(selector).removeClass("ignore");
						$form.find("input[name='start_ts']").val($start_ts);
						$form.find("input[name='end_ts']").val($end_ts);
						$form.find(".bEndTime .endLabel").text($end_label);
						$form.find(".bStartTime, .bEndTime, .bEmployee").show();
					
				}).on("change", "select[name='service_id']", function (e) {
					var $el = $(this),
						$opt = {},
						$form = $el.closest("form"),
						service_id = $form.find("select[name='service_id']").find("option:selected").val();
					
					$opt = {
							"id": service_id,
							"date": $form.find("input[name='date']").val(),
							"employee_id": $employee_id,
							"start_ts": $start_ts
						};
						
					$.get("index.php?controller=pjAdminBookings&action=pjActionGetService" + $as_pf, $opt ).done(function (data) {
						$form.find("#bookingServicesTime").html( $(data).find(".bookingServicesTime").html() ).show();
					});
					
					onChangeService();
				});
			} 
		}/* End Service */
		
		function addCustomer() {
			
			/* Search Customer */
			$dialogAddCustomer = $('body').find("#dialogAddCustomer");
			$boxCustomerInfo = $('body').find('#boxCustomerInfo');
			
			$dialogAddCustomer.on("click", ".buttonSearchCustomerInfo", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				if ($boxCustomerInfo.length > 0) {
					var $search = $('input[name=search_customer]').val();
					
					$boxCustomerInfo.html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionSearchCustomer&search=" + $search + $as_pf).done(function (data) {
						$boxCustomerInfo.html(data);
					});
				}
				
				return false;
			}).on("click", ".pj-paginator a", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $search = '',
					$page = '';
				
				if ($('#boxCustomerInfo').length > 0) {
					$search = "&search=" + $('input[name=search_customer]').val();
				}
				
				if ( $(this).hasClass("pj-paginator-list-active") ) {
					
				} else {
					$page = "&page=" + $(this).data("page");
					
					$('#boxCustomerInfo').html("");
					$.get("index.php?controller=pjAdminBookings&action=pjActionSearchCustomer" + $search + $page + $as_pf).done(function (data) {
						$('#boxCustomerInfo').html(data);
					});
				}
				
				return false;
			}); /* And Search */
			
			
			/* Add Customer */
			$('body').on("click", ".addCustomerInfo", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				if ($dialogAddCustomer.length > 0 && dialog) {
					$dialogAddCustomer.dialog("open");
					
				}
				 
				return false;
			});
			
			if ($dialogAddCustomer.length > 0 && dialog) {
				$dialogAddCustomer.dialog({
					modal: true,
					resizable: false,
					draggable: false,
					autoOpen: false,
					width: 600,
					open: function () {
						if ($boxCustomerInfo.length > 0) {
							$boxCustomerInfo.html("");
							$.get("index.php?controller=pjAdminBookings&action=pjActionSearchCustomer" + $as_pf).done(function (data) {
								$boxCustomerInfo.html(data);
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
		}
		
		function onChangeService() {
			var 
				$form = $('body').find('#serviceItemAdd'),
				service_id = $form.find("select[name='service_id']").find("option:selected").val(),
				$start_ts = $form.find("select[name='service_id']").find("option:selected").attr('data-start_ts'),
				$end_ts = $form.find("select[name='service_id']").find("option:selected").attr('data-end_ts'),
				$end_label = $form.find("select[name='service_id']").find("option:selected").attr('data-end'),
				selector = "input[name='employee_id'], input[name='start_ts'], input[name='end_ts']";
			
			if (parseInt(service_id, 10) > 0) {
				$form.find(selector).removeClass("ignore");
				$form.find("input[name='start_ts']").val($start_ts);
				$form.find("input[name='end_ts']").val($end_ts);
				$form.find(".bEndTime .endLabel").text($end_label);
				$form.find(".bStartTime, .bEndTime, .bEmployee").show();
			} else {
				$form.find(selector).addClass("ignore");
				$form.find(".bStartTime, .bEndTime, .bEmployee").hide();
				$form.find(selector).val("");
				$form.find(".data").text("---");
			}
		}
		
		function onChangeEmployee() {
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
		
		function getBookingItems(booking_id, tmp_hash) {
			$.get("index.php?controller=pjAdminBookings&action=pjActionItemGet" + $as_pf, {
				"booking_id": booking_id,
				"tmp_hash": tmp_hash
			}).done(function (data) {
				$("#boxBookingItems").html(data);
			});
		}
		
		$(document).on("focusin", ".datepick", function (e) {
			var $this = $(this);
			$this.datepicker({
				firstDay: $this.attr("rel"),
				dateFormat: $this.attr("rev"),
				onSelect: function (dateText, inst) {
					var $href = window.location.href.replace(/&date=(.*)&/,'&');
					
					$href = $href.replace(/&date=(.*)/,'');
					
					window.location.href = $href +'&date=' + [inst.selectedYear, inst.selectedMonth+1, inst.selectedDay].join("-");
				}
			});
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		});
		
		$( document ).ajaxStart(function() {
			$( "#loading" ).show();
		});
		
		$( document ).ajaxStop(function() {
			$( "#loading" ).hide();
		});
		
		$('.dWrapper').doubleScroll();
		
		$('.scroll-y').css("height", screen.height - 300);
	});
})(jQuery);
