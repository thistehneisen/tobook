<div class="TSBC_Width TSBC_Slot_Nav">
	<a href="#" class="TSBC_JS_Close TSBC_Close TSBC_Font"><?php echo date($tpl['option_arr']['date_format'], strtotime($_GET['date'])); ?><abbr>x</abbr></a>
</div>
<?php
if (!isset($tpl['dayoff']))
{	
	$step = $tpl['t_arr']['slot_length'] * 60;
	if ($tpl['option_arr']['calendar_width'] >= 400)
	{
		$numOfColumns = 2;
		$classOfTable = 'TSBC_Table_Half';
	} else {
		$numOfColumns = 1;
		$classOfTable = NULL;
	}
	# Fix for 24h support
	$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
	
	$numOfSlots = ceil(($offset + $tpl['t_arr']['end_ts'] - $tpl['t_arr']['start_ts']) / $step);
	$numOfSlotsPerColumn = ceil($numOfSlots / $numOfColumns);
	$firstHalfEndSlot = $tpl['t_arr']['start_ts'] + ($step * $numOfSlotsPerColumn);
	$now = time();
	?>
	<form action="" method="post" name="TSBC_Form_Timeslot" class="TSBC_Form TSBC_Font">
		<input type="hidden" name="booking_date" value="<?php echo $_GET['date']; ?>" />
		<?php
		ob_start();
		$total = 0;
		foreach (range(0, $numOfColumns - 1) as $column)
		{
			?>
			<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots TSBC_Font <?php echo $classOfTable; ?><?php echo $column == $numOfColumns - 1 ? ' TSBC_TableSlots_Last' : NULL; ?>">
				<thead>
					<tr>
						<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['start_time']; ?></th>
						<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['end_time']; ?></th>
						<th class="TSBC_Head">{PRICE}</th>
						<th class="TSBC_Head"></th>
					</tr>
				</thead>
				<tbody>
				<?php
				for ($i = $tpl['t_arr']['start_ts'] + ($numOfSlotsPerColumn * $step * $column); $i < $tpl['t_arr']['end_ts'] + $offset; $i += $step)
				{
					if ($column == 0 && $i >= $firstHalfEndSlot)
					{
						break;
					}
					$booked = 0;
					foreach ($tpl['bs_arr'] as $bs)
					{
						if ($bs['start_ts'] == $i && $bs['end_ts'] == $i + $step)
						{
							$booked++;
						}
					}
					if ($i < $now)
					{
						# Start Time is in past
						$state = 4;
						$class = "TSBC_Slot_Past";						
					} else {
						if ($booked < $tpl['t_arr']['slot_limit'])
						{
							$checked = NULL;
							if (isset($_SESSION[$controller->cartName][$_GET['cid']][$_GET['date']][$i . "|" . ($i + $step)]))
							{
								# In basket
								$state = 1;
								$class = "TSBC_Slot_Enabled";
							} else {
								# Available
								$state = 2;
								$class = "TSBC_Slot_Enabled";
							}
						} else {
							# Fully booked
							$state = 3;
							$class = "TSBC_Slot_Disabled";
						}
					}
					?>
					<tr class="<?php echo $class; ?>">
						<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
						<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
						<td><?php 
						if ($tpl['option_arr']['hide_prices'] == 'No' && (float) @$tpl['price_arr'][$i . "|" . ($i + $step)] > 0)
						{
							echo Util::formatCurrencySign(number_format(@$tpl['price_arr'][$i . "|" . ($i + $step)], 2, '.', ','), $tpl['option_arr']['currency']);
							$total += @$tpl['price_arr'][$i . "|" . ($i + $step)];
						}
						?></td>
						<td class="TSBC_Center">
						<?php
						switch ($state)
						{
							case 1:
								# In basket
								?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" checked="checked" class="TSBC_Slot" /><?php
								break;
							case 2:
								# Available
								?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" class="TSBC_Slot" /><?php
								break;
							case 3:
								# Fully booked
								?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" checked="checked" disabled="disabled" class="TSBC_Slot" /><?php
								break;
							case 4:
								# Past
								echo $TS_LANG['front']['cart']['passed'];
								break;
						}
						?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
		$content = ob_get_contents();
		ob_end_clean();
		$replacement = $tpl['option_arr']['hide_prices'] == 'No' && $total > 0 ? $TS_LANG['front']['cart']['price'] : NULL;
		echo str_replace('{PRICE}', $replacement, $content);
		?>
	</form>
	<div class="TSBC_Width TSBC_Slot_Nav">
		<a href="#" class="TSBC_JS_Proceed TSBC_Font"><?php echo $TS_LANG['front']['button_proceed']; ?></a>
	</div>
	<?php
} else {
	# Date/day is off
	echo $TS_LANG['booking_dayoff'];
}
?>