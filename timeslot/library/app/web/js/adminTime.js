(function ($) {
	$(function () {
		$(".working_day").click(function () {
			var checked = $(this).is(":checked"),
				$tr = $(this).closest("tr");
			$tr.find("select, input[type='text']").attr("disabled", checked);
			if (checked) {
				$tr.find("a.day-price").addClass("disabled");
			} else {
				$tr.find("a.day-price").removeClass("disabled");
			}
		});
		
		if ($("#tabs").length > 0) {
			$("#tabs").tabs();
		}
		
		if ($("#frmTimeCustom").length > 0) {
			
			$("#frmTimeCustom").validate();
			
			var dOpt = {
				dateFormat: 'yy-mm-dd'
			};
			if ($("#date").length > 0) {
				$("#date").datepicker(dOpt);
			}
			
			$(".pps").live("change", function () {
				
				if ($("#single_price").is(":checked")) {
					$("#boxPPS").html("");
				} else {
					$.ajax({
						type: "POST",
						data: $("#frmTimeCustom").serialize(),
						url: "index.php?controller=AdminTime&action=getSlots"
					}).success(function (data) {
						$("#boxPPS").html(data);
					});					
				}
			});
			
		}
		
		if ($("#dialogDayPrice").length > 0) {
			
			$("a.day-price").live("click", function (e) {
				e.preventDefault();
				if ($(this).hasClass("disabled"))
				{
					return;
				}
				$('#record_id').text($(this).attr('rev'));
				$('#dialogDayPrice').dialog('open');
			});
			
			$("#dialogDayPrice").dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				height:420,
				width: 460,
				modal: true,
				close: function(){
					$('#record_id').text('');
					$(this).html("");
				},
				open: function (event, ui) {
					var $this = $(this);
					$.get("index.php?controller=AdminTime&action=getPrices", {day: $('#record_id').text()}, function (data) {
						$this.html(data);
					});
				},
				buttons: {
					'Save': function() {
						var $this = $(this);
						$.ajax({
							type: "POST",
							data: $this.find("form").serialize(),
							url: "index.php?controller=AdminTime&action=setPrices"
						}).success(function (data) {
							$("#content").html(data);
							$("#tabs").tabs();
							$("#date").datepicker(dOpt);
						});
						$(this).dialog('close');			
					},
					'Delete All': function () {
						var $this = $(this);
						$.ajax({
							type: "POST",
							data: {
								"delete": 1,
								"day" : $this.find("form :input[name='day']").val()
							},
							url: "index.php?controller=AdminTime&action=setPrices"
						});
						$(this).dialog('close');
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
		
		$("a.icon-delete").live("click", function (e) {
			e.preventDefault();
			$('#record_id').text($(this).attr('rev'));
			$('#dialogDelete').dialog('open');
		});
		
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
							url: "index.php?controller=AdminTime&action=delete",
							success: function (res) {
								$("#tabs-2").html(res);
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
		
	});
})(jQuery);