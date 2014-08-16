<!-- Modified by Raccoon for bulk processing -->
<?php
foreach ($tpl['slots'] as $k=>$vtpl) { 
	
?>
<div class="TSBC_DIVnavBlock">
<div class="TSBC_CalName"><?php echo $vtpl['name']; ?></div>
<div class="TSBC_Width TSBC_Slot_Nav" style="float: none;">
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="TSBC_JS_Close TSBC_Close TSBC_Font" style="float: none;"><?php echo date($tpl['option_arr']['date_format'], strtotime($_GET['date'])); ?><abbr>x</abbr></a>
</div>
	<?php
	if (!isset($vtpl['dayoff']))
	{
		$step = $vtpl['t_arr']['slot_length'] * 60;
		$numOfColumns = 1;
		# Fix for 24h support
		$offset = $vtpl['t_arr']['end_ts'] <= $vtpl['t_arr']['start_ts'] ? 86400 : 0;
	
		$numOfSlots = ceil(($offset + $vtpl['t_arr']['end_ts'] - $vtpl['t_arr']['start_ts']) / $step);
		$numOfSlotsPerColumn = ceil($numOfSlots / $numOfColumns);
		$firstHalfEndSlot = $vtpl['t_arr']['start_ts'] + ($step * $numOfSlotsPerColumn);
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="TSBC_Form_Timeslot" class="TSBC_Form TSBC_Font">
			<input type="hidden" name="booking_date" value="<?php echo $_GET['date']; ?>" />
			<input type="hidden" name="calendar_id" value="<?php echo $k; ?>" />
			<?php
			$i = $vtpl['t_arr']['start_ts'];
			foreach (range(0, $numOfColumns - 1) as $column)
			{
				?>
				<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots TSBC_Table_Half<?php echo $column == $numOfColumns - 1 ? ' TSBC_TableSlots_Last' : NULL; ?>">
					<thead>
						<tr>
							<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['start_time']; ?></th>
							<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['end_time']; ?></th>
							<th class="TSBC_Head"><?php echo $TS_LANG['front']['cart']['price']; ?></th>
							<th class="TSBC_Head"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($i = $vtpl['t_arr']['start_ts'] + ($numOfSlotsPerColumn * $step * $column); $i < $vtpl['t_arr']['end_ts'] + $offset; $i += $step)
						{
							if ($column == 0 && $i >= $firstHalfEndSlot)
							{
								break;
							}
							$booked = 0;
							$self = false;
							foreach ($vtpl['bs_arr'] as $bs)
							{
								if (strtotime($bs['booking_date'] . " " . $bs['start_time']) == $i && strtotime($bs['booking_date'] . " " . $bs['end_time']) == $i + $step)
								{
									$booked++;
									if (isset($_GET['booking_id']) && $_GET['booking_id'] == $bs['booking_id'])
									{
										$self = true;
									}
								}
							}
							if ($booked < $vtpl['t_arr']['slot_limit'])
							{
								$checked = NULL;
								if (isset($_SESSION[$controller->cartName][$k][$_GET['date']][$i . "|" . ($i + $step)]))
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
								if ($self)
								{
									$state = 1;
									$class = "TSBC_Slot_Enabled";
								}
							}
							?>
							<tr class="<?php echo $class; ?>">
								<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
								<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
								<td><?php echo Util::formatCurrencySign(number_format(@$vtpl['price_arr'][$i . "|" . ($i + $step)], 2, '.', ','), $tpl['option_arr']['currency']); ?></td>
								<td class="TSBC_Center">
								<?php
								switch ($state)
								{
									case 1:
										?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $k;?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" checked="checked" class="TSBC_Slot" /><?php
										break;
									case 2:
										?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $k;?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" class="TSBC_Slot" /><?php
										break;
									case 3:
										?><input type="checkbox" name="timeslot[<?php echo $_GET['date']; ?>][<?php echo $k;?>][<?php echo $i; ?>]" value="<?php echo $i + $step; ?>" checked="checked" disabled="disabled" class="TSBC_Slot" /><?php
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
			?>
		</form>
		<?php
	} else {
		# Date/day is off
		?>
				<div><?php echo $TS_LANG['booking_dayoff']; ?></div>
		<?php
	}
	?>
	</div>
	<?php } 
	?>
<div class="TSBC_Width TSBC_Slot_Nav">
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="TSBC_JS_Proceed TSBC_Font"><?php echo $TS_LANG['front']['button_proceed']; ?></a>
</div>
		
<div style="color: red">
	<p id="err_1" style="display: none"><?php echo $TS_LANG['booking_err'][10]; ?></p>
</div>