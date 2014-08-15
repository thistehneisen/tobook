(function ($) {
	$(function () {		
		if ($('#frmCreateCalendar').length > 0) {
			$('#frmCreateCalendar').validate();
		}
		if ($('#frmUpdateCalendar').length > 0) {
			$('#frmUpdateCalendar').validate();
		}
	
		$("a.calendar-delete").live("click", function (e) {
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
							url: "index.php?controller=AdminCalendars&action=delete",
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
			
			function getSlots(cid, dt) {
				$.ajax({
					type: "GET",
					data: {"id": cid, "date": dt},
					url: "index.php?controller=AdminCalendars&action=getSlots",
					success: function (data) {
						$("#TSBC_Slots").html(data);
					}
				});
			}
			
			function getCalendar(id, m, y) {
				$.ajax({
					type: "GET",
					data: {id: id, month: m, year: y},
					url: "index.php?controller=AdminCalendars&action=getCalendar",
					success: function (data) {
						$(".TSBCalendar").eq(0).html(data);
					}
				});
			}
			
			var cid = $(".TSBCalendar").eq(0).attr("id").split("_")[1];
			
			$(".TSBCalendar .calendar, .TSBCalendar .calendarFull, .TSBCalendar .calendarPast").live("click", function () {
				getSlots.apply(null, [cid, $(this).attr("axis")]);
				if ($("#boxBookingDetails").length > 0) {$("#boxBookingDetails").show();}
			});
			
			var today = new Date(),
				m = today.getMonth() + 1,
				y = today.getFullYear();
			$(".TSBCalendar .calendarLinkMonth").live("click", function (e) {
				e.preventDefault();
				var rel = $(this).attr("rel"),
					id = $(".TSBCalendar").attr("id");
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
			
			$(".calendar").live("mouseover", function () {
				$(".calendarTooltip", this).css("visibility", "visible");
			});
			
			$(".calendar").live("mouseout", function () {
				$(".calendarTooltip", this).css("visibility", "hidden");
			});
			
			if ($("#dialogTimeslotDelete").length > 0) {
				
				$("a.timeslot-delete").live("click", function (e) {
					e.preventDefault();
					$('#record_id').text($(this).attr('rev'));
					$('#dialogTimeslotDelete').dialog('open');
				});
				
				$("#dialogTimeslotDelete").dialog({
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
							var rid = $('#record_id').text().split("|"),
								dt = rid[2].split("-");
							$.ajax({
								type: "POST",
								data: {
									id: rid[0]
								},
								url: "index.php?controller=AdminCalendars&action=deleteTimeslot",
								success: function (data) {
									getCalendar.apply(null, [rid[1], dt[1], dt[0]]);
									getSlots.apply(null, [rid[1], rid[2]]);
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
			
			if ($("#dialogBookingDelete").length > 0) {
				
				$("a.booking-delete").live("click", function (e) {
					e.preventDefault();
					$('#record_id').text($(this).attr('rev'));
					$('#dialogBookingDelete').dialog('open');
				});
				
				$("#dialogBookingDelete").dialog({
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
							var rid = $('#record_id').text().split("|"),
								dt = rid[2].split("-");
							$.ajax({
								type: "POST",
								data: {
									id: rid[0]
								},
								url: "index.php?controller=AdminCalendars&action=deleteBooking",
								success: function (data) {
									getCalendar.apply(null, [rid[1], dt[1], dt[0]]);
									getSlots.apply(null, [rid[1], rid[2]]);
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
		}
	});
})(jQuery);