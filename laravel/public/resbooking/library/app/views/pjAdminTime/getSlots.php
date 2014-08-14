<table cellpadding="0" cellspacing="0" class="tsbc-table">
	<thead>
		<tr>
			<th class="sub"><?php echo $RB_LANG['time_from']; ?></th>
			<th class="sub"><?php echo $RB_LANG['time_to']; ?></th>
			<th class="sub"><?php echo $RB_LANG['time_price']; ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$step = $_POST['slot_length'] * 60;
	$start_ts = strtotime($_POST['date'] . " " . $_POST['start_hour'] . ":" . $_POST['start_minute'] . ":00");
	$end_ts = strtotime($_POST['date'] . " " . $_POST['end_hour'] . ":" . $_POST['end_minute'] . ":00");
	
	# Fix for 24h support
	$offset = $end_ts <= $start_ts ? 86400 : 0;
		
	for ($i = $start_ts; $i < $end_ts + $offset; $i = $i + $step)
	{
		?>
		<tr>
			<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
			<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
			<td><input type="text" name="price[<?php echo $i; ?>|<?php echo $i + $step; ?>]" id="price_<?php echo $i; ?>" class="text w80 align_right" /> <?php echo $tpl['option_arr']['currency']; ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>