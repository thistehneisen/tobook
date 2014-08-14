<?php 

if ( $tpl['arr']['people'] >= $tpl['option_arr']['booking_group_booking'] ) { 
	$menu_name = '';
	isset($tpl['arr']['c_title']) ? $tpl['arr']['c_title'] . ' ' : '';
	$menu_name .= $tpl['arr']['c_lname'] . ' ' . $tpl['arr']['c_fname'];
	
	$menus = array();
	
	foreach ($tpl['menu_arr'] as $menu) {
		
		if ( in_array( $menu['id'], $tpl['bm_arr']) ) {
			$menus[$menu['m_type']][] = $menu['m_name'];
		}
	}
	
	?>
	<table class="table" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>Menu name</td>
				<td><?php echo $menu_name; ?></td>
			</tr>
			<?php foreach ($menus as $type => $menu) { ?>
			<tr>
				<td><?php echo isset($RB_LANG['menu_types'][$type]) ? $RB_LANG['menu_types'][$type] : $type; ?></td>
				<td><?php echo join(', ', $menu)?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else {
?>
<table class="table" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td><?php echo $RB_LANG['booking_id']; ?></td>
			<td><?php echo $tpl['arr']['id']; ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_uuid']; ?></td>
			<td><?php echo $tpl['arr']['uuid']; ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_dt']; ?></td>
			<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr']['dt'])); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_people']; ?></td>
			<td><?php echo (int) $tpl['arr']['people']; ?></td>
		</tr>
		<?php
		foreach ($tpl['table_arr'] as $k => $v)
		{
			if (!array_key_exists($v['id'], $tpl['bt_arr']))
			{
				continue;
			}
			?>
			<tr>
				<td><?php echo $RB_LANG['booking_table']; ?></td>
				<td><?php echo stripslashes($v['name']); ?></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td><?php echo $RB_LANG['booking_payment_method']; ?></td>
			<td><?php echo @$RB_LANG['_payments'][$tpl['arr']['payment_method']]; ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_is_paid']; ?></td>
			<td><?php echo @$RB_LANG['booking_is_paids'][$tpl['arr']['is_paid']]; ?></td>
		</tr>
		<tr style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
			<td><?php echo $RB_LANG['front']['4_cc_type']; ?></td>
			<td><?php echo @$RB_LANG['front']['4_cc_types'][$tpl['arr']['cc_type']]; ?></td>
		</tr>
		<tr style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
			<td><?php echo $RB_LANG['front']['4_cc_num']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['cc_num']); ?></td>
		</tr>
		<tr style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
			<td><?php echo $RB_LANG['front']['4_cc_exp']; ?></td>
			<td><?php echo $tpl['arr']['cc_exp']; ?></td>
		</tr>
		<tr style="display: <?php echo $tpl['arr']['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
			<td><?php echo $RB_LANG['front']['4_cc_code']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['cc_code']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_total']; ?></td>
			<td><?php echo pjUtil::formatCurrencySign(number_format(floatval($tpl['arr']['total']), 2), $tpl['option_arr']['currency'], " "); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_status']; ?></td>
			<td><?php echo @$RB_LANG['booking_statuses'][$tpl['arr']['status']]; ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_code']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['code']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_title']; ?></td>
			<td><?php echo @$RB_LANG['_titles'][$tpl['arr']['c_title']]; ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_fname']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_fname']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_lname']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_lname']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_phone']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_phone']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_email']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_email']); ?></td>
		</tr>
		
		<tr>
			<td><?php echo $RB_LANG['booking_company']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_company']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_address']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_address']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_city']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_city']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_state']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_state']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_zip']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['c_zip']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_country']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['country_title']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_created']; ?></td>
			<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr']['created'])); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_txn_id']; ?></td>
			<td><?php echo stripslashes($tpl['arr']['txn_id']); ?></td>
		</tr>
		<tr>
			<td><?php echo $RB_LANG['booking_processed_on']; ?></td>
			<td><?php echo !empty($tpl['arr']['processed_on']) ? date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr']['processed_on'])) : NULL; ?></td>
		</tr>
	</tbody>
</table>
<?php } ?>