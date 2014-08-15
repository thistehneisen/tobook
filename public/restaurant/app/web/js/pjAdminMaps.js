(function ($) {
	$(function () {
		var tOpts = {},
			$frmUpdateMap = $("#frmUpdateMap"),
			$dialogUpdate = $("#dialogUpdate"),
			$dialogDel = $("#dialogDel"),
			$tabs = $("#tabs"),
			$boxMap = $("#boxMap"),
			$content = $("#content");
			
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
		
		if ($frmUpdateMap.length > 0) {
			$frmUpdateMap.validate();
			tOpts = $.extend(tOpts, {
				select: function (event, ui) {
					$(":input[name='tab_id']").val(ui.panel.id);
				}
			});		
			
			$content.delegate(".button_delete", "click", function () {
				$dialogDel.data('rel', $(this).attr('rel')).dialog('open');
			});
			
			if ($dialogDel.length > 0) {
				$dialogDel.dialog({
					autoOpen: false,
					resizable: false,
					draggable: false,
					modal: true,
					buttons: {
						'Delete': function() {
							$.ajax({
								type: "POST",
								data: {
									id: $(this).data('rel')
								},
								url: "index.php?controller=pjAdminMaps&action=deleteFile" + $rbpf,
								success: function (data) {
									if (data.code && data.code == 7) {
										
									} else {
										$boxMap.html(data);
									}
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
			
			if ($dialogUpdate.length > 0) {
				$dialogUpdate.dialog({
					autoOpen: false,
					resizable: false,
					draggable: false,
					modal: true,
					width: 440,
					open: function () {
						var rel = $(this).data("rel"),
							arr = $("#" + rel).val().split("|");
						$("#seat_name").val(arr[5]);
						$("#seat_seats").val(arr[6]);
						$("#seat_minimum").val(arr[7]);
					},
					close: function () {
						$("#seat_name, #seat_seats, #seat_minimum").val("");
					},
					buttons: {
						'Save': function () {
							var rel = $(this).data("rel"),
								pName = $("#seat_name").val(),
								pSeats = $("#seat_seats").val(),
								pMin = $("#seat_minimum").val(),
								pHidden = $("#" + rel, $frmUpdateMap).val();
							$.post("index.php?controller=pjAdminMaps&action=saveSeat" + $rbpf, {
								"map_id": $(":input[name='id']", $frmUpdateMap).val(),
								"hidden": pHidden,
								"name": pName,
								"seats": pSeats,
								"minimum": pMin
							}).done(function (data) {
								if (!data.code) {
									return;
								}
								switch (parseInt(data.code, 10)) {
									case 200:
									case 201:
										var a = pHidden.split("|");
										$(".rect-selected[rel='" + rel + "']", $frmUpdateMap).text(pName);
										$("#" + rel).val([data.id, a[1], a[2], a[3], a[4], pName, pSeats, pMin].join("|"));
										$("input[name='m_name[" + data.id + "]']", $frmUpdateMap).val(pName);
										$("input[name='m_seats[" + data.id + "]']", $frmUpdateMap).val(pSeats);
										break;
								}
							});
							$(this).dialog('close');
						},
						'Delete': function() {
							var rel = $(this).data('rel');
							if (rel.split("_")[0] == 'hi') {
								$.post("index.php?controller=pjAdminMaps&action=deleteSeat" + $rbpf, {
									id: rel.split("_")[1]
								});
							}
							$("#" + rel, $("#hiddenHolder")).remove();				
							$(".rect-selected[rel='"+ rel +"']", $("#mapHolder")).remove();
							
							$(this).dialog('close');			
						},
						'Cancel': function() {
							$(this).dialog('close');
						}
					}
				});
			}
				
			function collisionDetect(o) {
				var i, pos, horizontalMatch, verticalMatch, collision = false;
				$("#mapHolder").children("span").each(function (i) {
					pos = getPositions(this);
					horizontalMatch = comparePositions([o.left, o.left + o.width], pos[0]);
					verticalMatch = comparePositions([o.top, o.top + o.height], pos[1]);			
					if (horizontalMatch && verticalMatch) {
						collision = true;
						return false;
					}
				});
				if (collision) {
					return true;
				}
				return false;
			}
			
			function getPositions(box) {
				var $box = $(box);
				var pos = $box.position();
				var width = $box.width();
				var height = $box.height();
				return [[pos.left, pos.left + width], [pos.top, pos.top + height]];
			}
			
			function comparePositions(p1, p2) {
				var x1 = p1[0] < p2[0] ? p1 : p2;
				var x2 = p1[0] < p2[0] ? p2 : p1;
				return x1[1] > x2[0] || x1[0] === x2[0] ? true : false;
			}
			
			function updateElem(event, ui) {
				var $this = $(this),
					rel = $this.attr("rel"),
					$hidden = $("#" + rel),
					val = $hidden.val().split("|");				
				$hidden.val([val[0], parseInt($this.width(), 10), parseInt($this.height(), 10), ui.position.left, ui.position.top, $this.text(), val[6], val[7]].join("|"));
			}
						
			var offset = $("#map").offset(),
				dragOpts = {
					containment: "parent",
					stop: function (event, ui) {
						updateElem.apply(this, [event, ui]);
					}
				};
			$("span.empty").draggable(dragOpts).bind("click", function (e) {
				$dialogUpdate.data('rel', $(this).attr("rel")).dialog("open");
				$(this).siblings(".rect").removeClass("rect-selected").end().addClass("rect-selected");
			});
			
			function getMax() {
				var tmp, index = 0;
				$("span.empty").each(function (i) {
					tmp = parseInt($(this).attr("rel").split("_")[1], 10);
					if (tmp > index) {
						index = tmp;
					}
				});
				return index;
			}
			
			$("#mapHolder").click(function (e) {
				var $this = $(this),
				index = getMax(),
				t = Math.ceil(e.pageY - offset.top - 13),
				l = Math.ceil(e.pageX - offset.left - 8),
				w = parseInt($("#width").val(), 10),
				h = parseInt($("#height").val(), 10),
				o = {top: t, left: l, width: w, height: h};
				if (!collisionDetect(o)) {
					index++;
					$("<span>", {
						css: {
							"top": t + "px",
							"left": l + "px",
							"width": w + "px",
							"height": h + "px",
							"line-height": h + "px",
							"position": "absolute"
						},
						text: index,
						rel: "hidden_" + index
					}).addClass("rect empty new").draggable(dragOpts).bind("click", function (e) {
						$dialogUpdate.data('rel', $(this).attr("rel")).dialog("open");
						$(this).siblings(".rect").removeClass("rect-selected").end().addClass("rect-selected");
					}).appendTo($this);
					
					$("<input>", {
						type: "hidden",
						name: "seats_new[]",
						id: "hidden_" + index
					}).val(['x', w, h, l, t, index, '1', '1'].join("|")).appendTo($("#hiddenHolder"));
					
				} else {
					if (window.console && window.console.log) {
						//console.log('Collision detected');
					}
				}
			});
		}
	
		if ($tabs.length > 0) {
			$tabs.tabs(tOpts);
		}
	});
})(jQuery);