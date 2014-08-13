<!-- Created by Raccoon for basket bulk processing  -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="TSBC_Form_Cart">
	
	<?php
	if (isset($tpl['slots']))
	{
		foreach ($tpl['slots'] as $calid => $vtpl ) 
		{
			$calendar_id = $calid;//isset($_GET['calendar_id']) ? $_GET['calendar_id'] : $controller->getCalendarId();
			if (count($vtpl['cart_arr']) > 0 && array_key_exists($calendar_id, $vtpl['cart_arr']) && count($vtpl['cart_arr'][$calendar_id]) > 0)
			{
				
	?>
				<div style="font-weight: bold; margin: 5px;"><?php echo $vtpl['cal_name']; ?></div>
		<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots" style="width: 100%">
			<thead>
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
				foreach ($vtpl['cart_arr'] as $cid => $date_arr)
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
					<td><?php echo Util::formatCurrencySign(number_format(@$vtpl['cart_price_arr'][$cid][$time], 2, '.', ','), $tpl['option_arr']['currency']); ?></td>
					<td class="TSBC_Center"><input type="checkbox" name="timeslot[<?php echo $date; ?>][<?php echo $calendar_id; ?>][<?php echo $start_ts; ?>]" value="<?php echo $end_ts; ?>" checked="checked" class="TSBC_Slot" rmvtype="bulk"/></td>
				</tr>
			<?php
							$total += @$vtpl['cart_price_arr'][$cid][$time];
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
			</table>
				<?php
			}
		}
	} else {
		?>
		<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots" style="width: 100%">
		<thead>
			<tr>
				<th class="TSBC_Head nosort"><?php echo $TS_LANG['front']['cart']['empty']; ?></th>
			</tr>
		</thead>
		</table>
		<?php  } ?>
	
</form>
<div style="color: red">
	<p id="err_1" style="display: none"><?php echo $TS_LANG['booking_err'][10]; ?></p>
</div>