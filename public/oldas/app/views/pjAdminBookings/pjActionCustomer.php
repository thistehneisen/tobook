<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	
	global $as_pf;
	
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>"><?php __('addBooking'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuInvoices'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCustomer&amp;as_pf=<?php echo $as_pf; ?>"><?php __('customer'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionStatistics&amp;as_pf=<?php echo $as_pf; ?>"><?php __('statistic'); ?></a></li>
		</ul>
	</div>
	<?php if (count($tpl['booking_arr'] > 0)) { ?>
	<div id="customer">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=download_csv&amp;as_pf=<?php echo $as_pf; ?>" target="_blank"><?php __("btnDownload");?></a>
		<table class="pj-table" style="width: 100%;">
			<thead>
				<tr>
					<th><?php __('booking_name', false, true); ?></th>
					<th><?php __('booking_phone', false, true); ?></th>
					<th><?php __('booking_email', false, true); ?></th>
					<th><?php __('booking_services', false, true); ?></th>
					<th><?php __('lblCount', false, true); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i = 0;
			foreach ($tpl['booking_arr'] as $booking) { 
				$i++;
				if ( $i%2 == 0 ) {
					$tr_class = "pj-table-row-even";
				} else {
					$tr_class = "pj-table-row-odd";
				}
			?>
				<tr class="<?php echo $tr_class; ?>">
					<td><?php echo $booking['c_name']; ?></td>
					<td><?php echo $booking['c_phone']; ?></td>
					<td><?php echo $booking['c_email']; ?></td>
					<td>
					<?php 
					if (count($tpl['service_arr']) > 0) {
						$service_name = array();
						foreach ($tpl['service_arr'] as $service) {
							if ($service['c_email'] == $booking['c_email']) {
								$service_name[] = $service['service_name']; 
							}
						}
						echo join(', ', $service_name);
					}
					?>
					</td>
					<td><?php echo $booking['count']; ?></td>
					<td><a class="pj-table-icon-edit customer-edit" href="#" data-id="<?php echo $booking['id']; ?>"></a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<div id="dialogCustomerInfo" title="Customer Info" style="display: none"></div>
	</div>
	
	<?php
	}
}
?>