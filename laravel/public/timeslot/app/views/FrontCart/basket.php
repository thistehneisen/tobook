<table cellpadding="2" cellspacing="1" class="TSBC_Table TSBC_Event TSBC_Header TSBC_Font">
	<thead>
		<tr>
			<th><?php echo $TS_LANG['front']['cart']['basket']; ?></th>
			<th style="width: 3%"><a href="#" class="TSBC_JS_Close">x</a></th>
		</tr>
	</thead>
</table>
<?php
if (isset($tpl['cart_arr']))
{
	if (count($tpl['cart_arr']) > 0 && array_key_exists($_GET['cid'], $tpl['cart_arr']) && count($tpl['cart_arr'][$_GET['cid']]) > 0)
	{
		ob_start();
		$total = 0;
		foreach ($tpl['cart_arr'] as $cid => $date_arr)
		{
			if ($cid != $_GET['cid'])
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
					<tr class="TSBC_Slot_Enabled">
						<td><?php echo date($tpl['option_arr']['date_format'], strtotime($_date)); ?></td>
						<td><?php echo date($tpl['option_arr']['time_format'], $start_ts); ?></td>
						<td><?php echo date($tpl['option_arr']['time_format'], $end_ts); ?></td>
						<td><?php echo $tpl['option_arr']['hide_prices'] == 'No' && (float) @$tpl['cart_price_arr'][$cid][$time] > 0 ? Util::formatCurrencySign(number_format(@$tpl['cart_price_arr'][$cid][$time], 2, '.', ','), $tpl['option_arr']['currency']) : NULL; ?></td>
						<td class="TSBC_Center"><input type="checkbox" name="timeslot[<?php echo $date; ?>][<?php echo $start_ts; ?>]" value="<?php echo $end_ts; ?>" checked="checked" class="TSBC_Slot" /></td>
					</tr>
					<?php
					$total += @$tpl['cart_price_arr'][$cid][$time];
				}
			}
		}
		$content = ob_get_contents();
		ob_end_clean();
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="TSBC_Form_Cart" class="TSBC_Form TSBC_Font">
			<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_TableSlots TSBC_Font">
				<thead>
					<tr>
						<th class="TSBC_Head TSBC_Date"><?php echo $TS_LANG['front']['cart']['date']; ?></th>
						<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['start_time']; ?></th>
						<th class="TSBC_Head TSBC_Time"><?php echo $TS_LANG['front']['cart']['end_time']; ?></th>
						<th class="TSBC_Head"><?php echo $tpl['option_arr']['hide_prices'] == 'No' && $total > 0 ? $TS_LANG['front']['cart']['price'] : NULL; ?></th>
						<th class="TSBC_Head TSBC_Center nosort">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php echo $content; ?>
				</tbody>
				<?php if ($tpl['option_arr']['hide_prices'] == 'No' && $total > 0) : ?>
				<tfoot>
					<tr>
						<td colspan="3" class="TSBC_Bold TSBC_Right"><?php echo $TS_LANG['front']['cart']['total']; ?>:</td>
						<td class="TSBC_Bold"><?php echo Util::formatCurrencySign(number_format($total, 2, '.', ','), $tpl['option_arr']['currency']); ?></td>
						<td>&nbsp;</td>
					</tr>
				</tfoot>
				<?php endif; ?>
			</table>
		</form>
		<?php
	} else {
		?>
		<table cellpadding="0" cellspacing="0" class="TSBC_Table TSBC_Font">
			<thead>
				<tr>
					<th class="TSBC_Head"><?php echo $TS_LANG['front']['cart']['empty']; ?></th>
				</tr>
			</thead>
		</table>
		<?php
	}
}
?>