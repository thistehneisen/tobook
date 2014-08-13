(function ($) {
	$(function () {
		if ($('#frmCreateUser').length > 0) {
			$('#frmCreateUser').validate();
		}
		if ($('#frmUpdateUser').length > 0) {
			$('#frmUpdateUser').validate();
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
							url: "index.php?controller=AdminUsers&action=delete",
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
	});
})(jQuery);