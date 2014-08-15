<?php
mt_srand();
$index = mt_rand(1, 9999);
$map = INSTALL_URL . UPLOAD_PATH . 'maps/map.jpg';
$size = @getimagesize($map);
?>
<div id="rbContainer_<?php echo $index; ?>" class="rbContainer"></div>
<div id="rbDialogTerms" title="<?php echo $RB_LANG['front']['4_terms_title']; ?>" style="display: none"></div>
<script type="text/javascript">
var RBooking_<?php echo $index; ?>;
(function () {
	var loadScript = function(url, callback) {
		var scr = document.getElementsByTagName("script"),
			s = scr[scr.length - 1],
			script = document.createElement("script");
		script.type = "text/javascript";
		if (script.readyState) {
			script.onreadystatechange = function () {
				if (script.readyState == "loaded" || script.readyState == "complete") {
					script.onreadystatechange = null;
					callback();
				}
			};
		} else {
			script.onload = function () {
				callback();
			};
		}
		script.src = url;
		s.parentNode.insertBefore(script, s.nextSibling);
	};
	var RBObj = {
		server: "<?php echo INSTALL_URL; ?>",
		folder: "<?php echo INSTALL_FOLDER; ?>",
		index: <?php echo $index; ?>,
		container_id: "rbContainer_<?php echo $index; ?>",

		booking_map_name: "RBookingMap_<?php echo $index; ?>",
		booking_map_image: "RBookingMapImage_<?php echo $index; ?>",
		use_map: <?php echo (int) $tpl['option_arr']['use_map']; ?>,

		days_off: [<?php echo isset($tpl['days_off']) && is_array($tpl['days_off']) && count($tpl['days_off']) > 0 ? join(",", $tpl['days_off']): NULL; ?>],
		dates_off: [<?php echo isset($tpl['dates_off']) && is_array($tpl['dates_off']) && count($tpl['dates_off']) > 0 ? '"'. join('","', $tpl['dates_off']) . '"' : NULL; ?>],
		dates_on: [<?php echo isset($tpl['dates_on']) && is_array($tpl['dates_on']) && count($tpl['dates_on']) > 0 ? '"'. join('","', $tpl['dates_on']) . '"' : NULL; ?>],
		date_format: "<?php echo $tpl['option_arr']['date_format']; ?>",
		day_names: ["<?php echo join('","', $RB_LANG['day_name']); ?>"],
		month_names_full: ["<?php echo join('","', $RB_LANG['months_full']); ?>"],
		validation: {
			error_search_1: "<?php echo $RB_LANG['front']['v_err_search_1']; ?>",
			error_search_2: "<?php echo $RB_LANG['front']['v_err_search_2']; ?>",
			error_search_3: "<?php echo $RB_LANG['front']['v_err_search_3']; ?>",
			error_search_4: "<?php echo $RB_LANG['front']['v_err_search_4']; ?>",
			error_search_5: "<?php echo $RB_LANG['front']['v_err_search_5']; ?>",
			error_search_6: "<?php echo $RB_LANG['front']['v_err_search_6']; ?>",
			error_search_7: "<?php echo $RB_LANG['front']['v_err_search_7']; ?>",
			error_title: "<?php echo $RB_LANG['front']['4_v_err_title']; ?>",
			error_email: "<?php echo $RB_LANG['front']['4_v_err_email']; ?>",
			error_map_1: "<?php echo $RB_LANG['front']['v_err_map_1']; ?>",
			error_map_2: "<?php echo $RB_LANG['front']['v_err_map_2']; ?>",
			error_map_3: "<?php echo $RB_LANG['front']['v_err_map_3']; ?>",
			error_map_4: "<?php echo $RB_LANG['front']['v_err_map_4']; ?>",
			error_map_5: "<?php echo $RB_LANG['front']['v_err_map_5']; ?>",
			error_map_6: "<?php echo $RB_LANG['front']['v_err_map_6']; ?>",
			error_map_7: "<?php echo $RB_LANG['front']['v_err_map_7']; ?>"
		},
		message_1: "<?php echo $RB_LANG['front']['msg_1']; ?>",
		message_2: "<?php echo $RB_LANG['front']['msg_2']; ?>",
		message_3: "<?php echo $RB_LANG['front']['msg_3']; ?>",
		message_4: "<?php echo $RB_LANG['front']['msg_4']; ?>",
		message_5: "<?php echo $RB_LANG['front']['msg_5']; ?>",
		map_width: <?php echo isset($size) && isset($size[0]) ? $size[0] : 0; ?>,
		map_height: <?php echo  isset($size) && isset($size[1]) ? $size[1] : 0; ?>,
		close_button: "<?php echo $RB_LANG['front']['4_close']; ?>"
	};
	loadScript("<?php echo INSTALL_URL . JS_PATH; ?>jabb-0.4.1.js", function () {
		loadScript("<?php echo INSTALL_URL . LIBS_PATH; ?>calendarJS/calendar.min.js", function () {
			loadScript("<?php echo INSTALL_URL . LIBS_PATH; ?>overlayJS/overlay.min.js", function () {
				loadScript("<?php echo INSTALL_URL . JS_PATH; ?>RBooking.js", function () {
					RBooking_<?php echo $index; ?> = new RBooking(RBObj);
				});
			});
		});
	});
})();
</script>