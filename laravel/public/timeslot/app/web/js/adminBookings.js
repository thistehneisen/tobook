(function ($) {
	$(function () {		
		
		var Cart = {
			add: function (postData) {
				$.ajax({
					type: "POST",
					data: postData,
					url: "index.php?controller=AdminCart&action=add"
				}).success(function () {
					getPrices();
				});
			},
			bulkremove: function (postData, loadBasket, calendar_id) {
				$.ajax({
					type: "POST",
					data: $.extend(postData, {"calendar_id": calendar_id}),
					url: "index.php?controller=AdminCart&action=bulkremove"
				}).success(function (data) {
					if (loadBasket) {
						Cart.bulkbasket();
					} else {
						// getPrices();
					}
				});
			},
			remove: function (postData, loadBasket, calendar_id) {
				$.ajax({
					type: "POST",
					data: $.extend(postData, {"calendar_id": calendar_id}),
					url: "index.php?controller=AdminCart&action=remove"
				}).success(function (data) {
					if (loadBasket) {
						Cart.basket(calendar_id);
					} else {
						getPrices();
					}
				});
			},
			bulkbasket: function() {
				$.ajax({
					type: "GET",
					data: {"basket_type": 1},
					url: "index.php?controller=AdminCart&action=bulkbasket"
				}).success(function (data) {
					//alert (data);
					$("#TSBC_Slots").html(data);
					
					JABB.Sort.that = JABB.Sort;
					JABB.Sort.makeSortable(JABB.Utils.getElementsByClass("TSBC_TableSlots")[0]);
					
					// getBulkPrice();
				});
			},
			basket: function (calendar_id) {
				$.ajax({
					type: "GET",
					data: {"calendar_id": calendar_id},
					url: "index.php?controller=AdminCart&action=basket"
				}).success(function (data) {
					$("#TSBC_Slots").html(data);
					
					JABB.Sort.that = JABB.Sort;
					JABB.Sort.makeSortable(JABB.Utils.getElementsByClass("TSBC_TableSlots")[0]);
					
					getPrices();
				});
			}
		};
		
		var calendar_id = undefined;
		
		if ($('#frmCreateBooking').length > 0 || $('#frmUpdateBooking').length > 0) {

			$.validator.addMethod("num_of_slots", function(value) {
				return $(":input.TSBC_Slot").length > 0;
			}, 'At least one slot is required');

			var vOpts = {
				rules: {
					num_of_slots: "num_of_slots"
				}
			};
			
			$("#payment_method").live("change", function () {
				if ($(this).val() == 'creditcard') {
					$(".cc").show();
				} else {
					$(".cc").hide();
				}
			});
		}
		
		if ($('#frmCreateBooking').length > 0) {
			$('#frmCreateBooking').validate(vOpts);
		}
		
		if ($('#frmUpdateBooking').length > 0) {
			$('#frmUpdateBooking').validate(vOpts);
			
			calendar_id = $("#frmUpdateBooking :input[name='calendar_id']").val();
			Cart.basket(calendar_id);
		}
		
		$("a.icon-delete").live("click", function (e) {
			e.preventDefault();
			$('#record_id').text($(this).attr('rev'));
			$('#dialogDelete').dialog('open');
		});
		
		if ($("#tabs").length > 0) {
			$("#tabs").tabs();
		}
		
		if ($("#dialogDelete").length > 0) {
			$("#dialogDelete").dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				height:220,
				modal: true,
				close: function(){
					$('#record_id').text('');
				},
				buttons: {
					'Delete': function() {
						$.ajax({
							type: "POST",
							data: {
								id: $('#record_id').text()
							},
							url: "index.php?controller=AdminBookings&action=delete",
							success: function (res) {
								$("#content").html(res);
								$("#tabs").tabs();
							}
						});
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
		
		if ($(".TSBCalendar").length > 0) {
		
			function getSlots(cid, dt, booking_id){
				$.ajax({
					type: "GET",
					data: {
						"id": cid,
						"booking_id": booking_id,
						"date": dt
					},
					url: "index.php?controller=AdminBookings&action=getSlots",
					success: function(data){
						$("#TSBC_Slots").html(data);
					}
				});
			}
			
			function getCalendar(id, m, y){
				$.ajax({
					type: "GET",
					data: {
						id: id,
						month: m,
						year: y
					},
					url: "index.php?controller=AdminCalendars&action=getCalendar",
					success: function(data){
						$(".TSBCalendar").eq(0).html(data);
					}
				});
			}
			
			function getPrices(calendar_id) {
				$.getJSON("index.php?controller=AdminBookings&action=getPrices", {"calendar_id": calendar_id}, function () {
					
				}).success(function (json) {
					switch (json.code) {
						case 200:
							$("#booking_total").val(json.price);
							$("#booking_deposit").val(json.deposit);
							$("#booking_tax").val(json.tax);
							break;
						case 101:
							//TODO
							break;
					}
				});
			}
						
			// Add "click", "mouseover" and "mouseout" event to rows
			$("tr.TSBC_Slot_Enabled").live("mouseover", function() {
				$(this).addClass('TSBC_Slot_Hover');
			}).live("mouseout", function() {
				$(this).removeClass('TSBC_Slot_Hover');
			}).live("click", function (e) {
				if (e.target.type !== 'checkbox') {
					handleClick.apply($(":input.TSBC_Slot", $(this)), [false]);
				}						
			});
			
			$("tr.TSBC_Slot_Enabled :input.TSBC_Slot").live("change", function (e) {
				$(this).parent().parent().addClass("TSBC_Handle");
				handleClick.apply($(this), [true]);
			});

			function handleClick(isCheckbox) {
				var row = this.parent().parent(),
					isHandle = row.hasClass("TSBC_Handle");// Tells whether or not checkbox is clicked
				if ((!this.is(":checked") && !isHandle) || (this.is(":checked") && isHandle)) {
					if (!isCheckbox) {
						this.attr("checked", true);
					}
					var post = $(":input.TSBC_Slot").serialize();
					Cart.add.apply(row, [post]);
				} else {
					if (!isCheckbox) {
						this.attr("checked", false);
					}
					var obj = {};
					obj[this.attr("name")] = this.val();
					
					Cart.remove.apply(row, [obj, false, calendar_id]);
				}
				row.removeClass("TSBC_Handle");
			}
			
			// Add "click", "mouseover" and "mouseout" event to rows
			$("tr.TSBC_Slot_Cart").live("mouseover", function() {
				$(this).addClass('TSBC_Slot_Remove');
			}).live("mouseout", function() {
				$(this).removeClass('TSBC_Slot_Remove');
			}).live("click", function () {
				
				var timeslot = $("input.TSBC_Slot", $(this)).eq(0);
				timeslot.attr("checked", false);
				
				var obj = {};
				obj[timeslot.attr("name")] = timeslot.val();
				// Added by Raccoon
				if (timeslot.attr("rmvtype") == "bulk") {
					Cart.bulkremove.apply($(this), [obj, true, calendar_id]);
				} else {
					Cart.remove.apply($(this), [obj, true, calendar_id]);
				}
				
			});

			$("a.TSBC_JS_Proceed").live("click", function (e) {
				if (e.preventDefault) {
					e.preventDefault();
				}
				// Cart.basket();
				Cart.bulkbasket();
				$("#boxBookingDetails").show();
			});
			$("a.TSBC_JS_Close").live("click", function (e) {
				if (e.preventDefault) {
					e.preventDefault();
				}
				$("#TSBC_Slots").html("");
			});
			
			var cid = $(".TSBCalendar").eq(0).attr("id").split("_")[1];
			
			$(".TSBCalendar .calendar, .TSBCalendar .calendarPast").live("click", function(){
				getSlots.apply(null, [cid, $(this).attr("axis"), $(":input[name='id']").val()]);
			}).live("mouseover", function(){
				$(".calendarTooltip", this).css("visibility", "visible");
			}).live("mouseout", function(){
				$(".calendarTooltip", this).css("visibility", "hidden");
			});
			
			var today = new Date(), m = today.getMonth() + 1, y = today.getFullYear();
			$(".TSBCalendar .calendarLinkMonth").live("click", function(e){
				e.preventDefault();
				var rel = $(this).attr("rel"), id = $(".TSBCalendar").attr("id");
				switch (rel.split("-")[0]) {
					case 'next':
						y = m + 1 > 12 ? y + 1 : y;
						m = m + 1 > 12 ? m + 1 - 12 : m + 1;
						break;
					case 'prev':
						y = m - 1 < 1 ? y - 1 : y;
						m = m - 1 < 1 ? m - 1 + 12 : m - 1;
						break;
				}
				getCalendar.apply(null, [id.split("_")[1], m, y]);
				$("#TSBC_Slots").html("");
			});
		}
		
		if ($("#frmExportBooking").length > 0) {
			var eOpt = {
				dateFormat: 'yy-mm-dd'
			};
			$("#date_from").datepicker($.extend(eOpt, {
				onSelect: function (dateText, inst) {
					$("#date_to").datepicker("option", "minDate", dateText);
				}
			}));
			$("#date_to").datepicker($.extend(eOpt, {
				
			}));
		}
	});
})(jQuery);