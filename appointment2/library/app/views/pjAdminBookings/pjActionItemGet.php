<?php
if (isset($tpl['bi_arr']) && !empty($tpl['bi_arr']))
{
	global $as_pf;
	?>
	<table class="pj-table b10 float_left" cellpadding="0" cellspacing="0" style="width: 75%">
		<thead>
			<tr>
				<th><?php __('booking_service_employee'); ?></th>
				<th class="w110"><?php __('booking_dt'); ?></th>
				<th class="w90 align_right"><?php __('booking_price'); ?></th>
				<th class="w90">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($tpl['bi_arr'] as $item)
		{
			?>
			<tr>
				<td>
					<?php echo pjSanitize::html($item['service']); ?>
					<br/>
					<?php echo pjSanitize::html($item['employee']); ?>
				</td>
				<td><?php echo date($tpl['option_arr']['o_datetime_format'], $item['start_ts']); ?></td>
				<td class="align_right"><?php echo pjUtil::formatCurrencySign(number_format($item['price'], 2), $tpl['option_arr']['o_currency']); ?></td>
				<td><!-- <?php if (!isset($_GET['tmp_hash'])) : ?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;as_pf=<?php echo $as_pf; ?>" class="pj-table-icon-email item-email" data-id="<?php echo $item['id']; ?>"></a><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings" class="pj-table-icon-phone item-sms" data-id="<?php echo $item['id']; ?>"></a><?php endif; ?>--><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings" class="pj-table-icon-delete item-delete" data-id="<?php echo $item['id']; ?>"></a></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
} else {
	?><span class="left"><?php __('booking_i_empty'); ?></span><?php
}
?>