<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="TSBC_Form_Cart">
	<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots" style="width: 100%">
		<thead>
	<?php
	if (isset($tpl['cart_arr']))
	{
		$calendar_id = isset($_GET['calendar_id']) ? $_GET['calendar_id'] : $controller->getCalendarId();
		if (count($tpl['cart_arr']) > 0 && array_key_exists($calendar_id, $tpl['cart_arr']) && count($tpl['cart_arr'][$calendar_id]) > 0)
		{
			?>
				<tr>
					<th class="TSBC_Head TSBC_Date"><?php echo $TS_LANG['front']['cart']['date']; ?></th>
					<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['start_time']; ?></th>
					<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['end_time']; ?></th>
					<th class="TSBC_Head"><?php echo $TS_LANG['front']['cart']['price']; ?></th>
					<th class="TSBC_Head TSBC_Center nosort">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$total = 0;
				foreach ($tpl['cart_arr'] as $cid => $date_arr)
				{
					if ($cid != $calendar_id)
					{
						continue;
					}
					foreach ($date_arr as $date => $time_arr)
					{
						foreach ($time_arr as $time => $q)
						{
							list($start_ts, $end_ts) = explode("|", $time);
							$sd = date("Y-m-d", $start_ts);
							$_date = $date == $sd ? $date : $sd;
							?>
							<tr class="TSBC_Slot_Cart">
								<td><?php echo date($tpl['option_arr']['date_format'], strtotime($_date)); ?></td>
								<td><?php echo date($tpl['option_arr']['time_format'], $start_ts); ?></td>
								<td><?php echo date($tpl['option_arr']['time_format'], $end_ts); ?></td>
								<td><?php echo Util::formatCurrencySign(number_format(@$tpl['cart_price_arr'][$cid][$time], 2, '.', ','), $tpl['option_arr']['currency']); ?></td>
								<td class="TSBC_Center"><input type="checkbox" name="timeslot[<?php echo $date; ?>][<?php echo $start_ts; ?>]" value="<?php echo $end_ts; ?>" checked="checked" class="TSBC_Slot" /></td>
							</tr>
							<?php
							$total += @$tpl['cart_price_arr'][$cid][$time];
						}
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="align_right">
						
					</td>
				</tr>
			</tfoot>
			<?php
		} else {
			?>
				<tr>
					<th class="TSBC_Head nosort"><?php echo $TS_LANG['front']['cart']['empty']; ?></th>
				</tr>
			</thead>
			<?php
		}
	}
	?>
	</table>
</form>
<div style="color: red">
	<p id="err_1" style="display: none"><?php echo $TS_LANG['booking_err'][10]; ?></p>
</div>