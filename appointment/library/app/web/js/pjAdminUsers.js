var jQuery_1_8_2 = $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateUser = $("#frmCreateUser"),
			$frmUpdateUser = $("#frmUpdateUser"),
			datagrid = ($.fn.datagrid !== undefined);
		
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
		
		if ($frmCreateUser.length > 0) {
			$frmCreateUser.validate({
				rules: {
					"email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionCheckEmail"
					}
				},
				messages: {
					"email": {
						remote: "Email address is already in use"
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
		if ($frmUpdateUser.length > 0) {
			$frmUpdateUser.validate({
				rules: {
					"email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionCheckEmail&id=" + $frmUpdateUser.find("input[name='id']").val()
					}
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
		
		if ($("#grid").length > 0 && datagrid) {
			
			function formatDefault (str, obj) {
				if (obj.role_id == 3) {
					return '<a href="#" class="pj-status-icon pj-status-' + (str == 'F' ? '0' : '1') + '" style="cursor: ' +  (str == 'F' ? 'pointer' : 'default') + '"></a>';
				} else {
					return '<a href="#" class="pj-status-icon pj-status-1" style="cursor: default"></a>';
				}
			}
			function formatRole (str) {
				return ['<span class="label-status user-role-', str, '">', str, '</span>'].join("");
			}
			
			function onBeforeShow (obj) {
				if (parseInt(obj.id, 10) === pjGrid.currentUserId) {
					return false;
				}
				return true;
			}
			
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionDeleteUser&id={:id}", beforeShow: onBeforeShow}
				          ],
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: true},
				          {text: myLabel.email, type: "text", sortable: true, editable: true},
				          {text: myLabel.created, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				          {text: myLabel.role, type: "text", sortable: true, renderer: formatRole},
				          {text: myLabel.confirmed, type: "text", sortable: true, editable: false, width: 80, renderer: formatDefault, align: "center"},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [
				                                                                                     {label: myLabel.active, value: "T"}, 
				                                                                                     {label: myLabel.inactive, value: "F"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminUsers&action=pjActionGetUser" + $as_pf,
				dataType: "json",
				fields: ['name', 'email', 'created', 'role', 'is_active', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionDeleteUserBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.revert_status, url: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionStatusUser", render: true},
					   {text: myLabel.exported, url: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionExportUser", ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminUsers"+ $as_pf +"&action=pjActionSaveUser&id={:id}",
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
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminUsers&action=pjActionGetUser" + $as_pf, "name", "ASC", content.page, content.rowCount);
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
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminUsers&action=pjActionGetUser" + $as_pf, "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pj-status-1", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			return false;
		}).on("click", ".pj-status-0", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.post("index.php?controller=pjAdminUsers&action=pjActionSetActive" + $as_pf, {
				id: $(this).closest("tr").data("object")['id']
			}).done(function (data) {
				$grid.datagrid("load", "index.php?controller=pjAdminUsers&action=pjActionGetUser" + $as_pf);
			});
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
			$grid.datagrid("load", "index.php?controller=pjAdminUsers&action=pjActionGetUser" + $as_pf, "id", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);