<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="day" value="<?php echo $_GET['day']; ?>" />
	<table cellpadding="0" cellspacing="0" class="tsbc-table">
		<thead>
			<tr>
				<th colspan="3"><?php echo $TS_LANG['days'][$_GET['day']];?></th>
			</tr>
			<tr>
				<th class="sub"><?php echo $TS_LANG['time_from']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_to']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_price']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		#$step = $tpl['option_arr']['slot_length'] * 60;
		$step = $tpl['wt_arr'][$_GET['day'] . '_length'] * 60;
		$start_ts = strtotime($tpl['wt_arr'][$_GET['day'] . '_from']);
		$end_ts = strtotime($tpl['wt_arr'][$_GET['day'] . '_to']);
		
		# Fix for 24h support
		$offset = $end_ts <= $start_ts ? 86400 : 0;
		
		for ($i = $start_ts; $i < $end_ts + $offset; $i = $i + $step)
		{
			$price = NULL;
			foreach ($tpl['price_day_arr'] as $k => $v)
			{
				#if (strtotime($v['start_time']) == $i && strtotime($v['end_time']) == $i + $step)
				if ($v['start_time'] == date("H:i:s", $i) && $v['end_time'] == date("H:i:s", $i + $step))
				{
					$price = $v['price'];
					break;
				}
			}
			?>
			<tr>
				<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
				<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
				<td><input type="text" name="price[<?php echo $i; ?>|<?php echo $i + $step; ?>]" id="price_<?php echo $i; ?>" class="text w80 align_right" value="<?php echo $price; ?>" /> <?php echo $tpl['option_arr']['currency']; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</form>