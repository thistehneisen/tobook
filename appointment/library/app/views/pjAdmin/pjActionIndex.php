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
	
	if ( isset($_GET['date']) && !empty($_GET['date']) ) {
		$_SESSION[$as_pf . 'url_date'] = $_GET['date'];
		
	} else unset($_SESSION[$as_pf . 'url_date']);
		
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['AD01'], @$bodies['AD01'], true, false);
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	
	$booking_statuses = __('booking_statuses', true, true);
	$invoice_statuses = __('plugin_invoice_statuses', true, true);
	$filter = __('filter', true, true);
	
	$week = array(
			
			'Mon' => 'Ma',
			'Tue' => 'Ti',
			'Wed' => 'Ke',
			'Thu' => 'To',
			'Fri' => 'Pe',
			'Sat' => 'La',
			'Sun' => 'Su'
		);
	
	$now = isset($_GET['date']) && !empty($_GET['date']) ? strtotime($_GET['date']) : strtotime('now');
	
	$i = 0;
	$_now = 0;
	foreach ($week as $key => $day) {
		
		if ( $key == date('D', $now) ) {
			$_now = $i;
			break;
		}
		$i++;
	}
	?>
	<div class="b10">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d'); ?>" class="pj-button btnToday float_left inline_block r5"><?php __('btnToday'); ?></a>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', strtotime("+1 day")); ?>" class="pj-button btnTomorrow float_left inline_block r5"><?php __('btnTomorrow'); ?></a>
		<span class="float_left">
			<span class="pj-form-field-custom pj-form-field-custom-after">
				<input type="text" name="dt" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? date($tpl['option_arr']['o_date_format'], strtotime($_GET['date'])) : date($tpl['option_arr']['o_date_format']); ?>" />
				<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
			</span>
		</span>
		
		<ul class="week-button float_left">
			<li class="prev-week"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', $now - 604800); ?>" title="Previous week">Previous week</a></li>
			<li class="prev"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', $now - 86400); ?>" title="Previous">Previous</a></li>
			<li class="next"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', $now + 86400); ?>" title="Next">Next</a></li>
			<li class="next-week"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', $now + 604800); ?>" title="Next week">Next week</a></li>
		</ul>
		
		<ul class="week-day float_left">
		<?php 
		$i = 0;
		$_d = date('d', $now);
		$count_days = cal_days_in_month(CAL_GREGORIAN, date('m', $now), date('Y', $now));
		foreach ($week as $key => $day) { 
			$d = $_d + ( $i - $_now );
			$m = date('m', $now);
			$y = date('Y', $now);
			
			if ( $i - $_now == 0 ) {
				$active = 'active';
				
			} else $active = '';
			
			if ( $d < 1 ) {
				$m = $m - 1;
				if ( $m < 1 ) {
					$m = 12;
					$y = $y - 1;
				}
				
				$_count_days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
				$d = $_count_days + $d;
				
			} elseif ( $d > $count_days ) {
				$m = $m + 1;
				if ( $m > 12 ) {
					$m = 1;
					$y = $y + 1;
				}
				
				$d = $d - $count_days;
			}
		?>
			<li><a class="pj-button <?php echo $active; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo date('Y-m-d', strtotime($y . '-' . $m . '-' . $d)); ?>"><?php echo $day; ?></a></li>
		<?php $i++;
		} ?>
		</ul>
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionPrint&amp;as_pf=<?php echo $as_pf; ?>&amp;date=<?php echo isset($_GET['date']) && !empty($_GET['date']) ? urlencode($_GET['date']) : date("Y-m-d"); ?>" target="_blank" class="pj-button btnPrint float_right inline_block"><?php __('report_print'); ?></a>
		<br class="clear_both" />
	</div>
	<?php
	if (!$tpl['t_arr'])
	{
		pjUtil::printNotice(@$titles['AD02'], @$bodies['AD02'], true, false);
	} else {
		if (empty($tpl['service_arr']))
		{
			
		} else {
			# Fix for 24h support
			$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
			$step = $tpl['option_arr']['o_step'] * 60;
			?>
			<div class="dContainer">
				<div class="dWrapper"><div class="scroll-y">
					<table class="pj-table dTable" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<td class="dHead"><span></span></td>
							<?php
							foreach ($tpl['employee_arr'] as $employee)
							 {
							?><td class="dHead"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionEmployeeWeek&amp;as_pf=<?php echo $as_pf; ?>&amp;employee_id=<?php echo $employee['id']; ?>"><?php echo pjSanitize::html($employee['name']); ?></a></td><?php
							}
							?>
							</tr>
						</thead>
						<tbody>
							<?php
							for ($i = $tpl['t_arr']['start_ts']; $i <= $tpl['t_arr']['end_ts'] + $offset - $step; $i += $step)
							{
								?>
								<tr>
									<td class="dHead"><span><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></span></td>
									<?php
									
									foreach ($tpl['employee_arr'] as $employee)
									{
										
										if ( $employee['t_arr']['client'] == false ||
												$i < $employee['t_arr']['client']['start_ts'] ||
												$i >= $employee['t_arr']['client']['end_ts'] ) {
											$class = '';
												
										} else {
											$class = 'client';
										}
										
										$bookings = array();
										
										foreach ($tpl['bs_arr'] as $item)
										{
											if ($employee['id'] == $item['employee_id'] && $i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60)
											{
												$bookings[] = $item;
												
												if ( isset($item['status']) && !empty($item['status']) ) {
													$booking_status = $item['status'];
														
												} else $booking_status = $item['booking_status'];
												
												$class = 'asSlotBooked ' . $booking_status;
												if ($employee['id'] == $item['employee_id'] && $i - $step >= $item['start_ts'] && $i - $step < $item['start_ts'] + $item['total'] * 60){
													$class .= ' asSlotBookedPlus';
												}
											}
										}
										
										$freetime = array();
										$ft_first = false;
										foreach ( $employee['ef_arr'] as $_freetime ) {
										
											if ($i >= $_freetime['start_ts'] && $i < $_freetime['end_ts']) {
												$class = "asSlotFreetime";
												$freetime = $_freetime;
												
												if ($i - $step < $_freetime['start_ts']){
													$ft_first = true;
												}
												break;
											}
										}
										
										?><td class="dSlot <?php echo $class; ?>"><?php 
										//var_dump($i);var_dump($employee['t_arr']['client']);
										if ( $employee['t_arr']['admin'] != false && ($i < $employee['t_arr']['admin']['start_ts'] ||
												$i > $employee['t_arr']['admin']['end_ts'])) {
												
										} elseif (empty($bookings))
										{
											echo '<div style="text-align: center;">';
											echo '<span style="float: left;">'. date($tpl['option_arr']['o_time_format'], $i) .'</span>';
											
											if ( count($freetime) > 0 && ($freetime['start_ts'] < $employee['t_arr']['client']['start_ts'] || $freetime['end_ts'] > $employee['t_arr']['client']['end_ts'])) {
												echo $freetime['message'];
												
											} else {
												echo '<a class="dashboardView" href="#" data-employee_id="'. $employee['id'] .'" data-start_ts="'. $i .'" >';
												
												if ( isset($freetime['message']) && !empty($freetime['message'])) {
													echo $freetime['message'];
													
												} else echo  'varaa'; //date($tpl['option_arr']['o_time_format'], $i);
												
												echo  '</a>';
											}
											
											if ($ft_first) {
												echo '<a href="#" class="removeFreetime" data-freetime_id="'. $freetime['id'] .'" title="Remove Freetime">x</a>';
											}
											echo '</div>';
											
										} else {
											foreach ($bookings as $booking)
											{
												if ( isset($booking['status']) && !empty($booking['status']) ) {
													$booking_status = $booking['status'];
													
												} else $booking_status = $booking['booking_status'];
												?>
												<div class="">
													<a class="editbooking" data-booking_id="<?php echo $booking['booking_id']; ?>" data-employee_id="<?php echo $employee['id']; ?>" data-start_ts="<?php echo $i; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo $booking['booking_id']; ?>"><?php echo pjSanitize::html($booking['c_name']); ?>
													<span class="fs11"> (<?php echo pjSanitize::html($booking['service_name']); ?>)</span>
													</a>
													<?php //if ( $tpl['option_arr']['o_custom_status'] == 1 ) { ?>
													<a class="dashboardView moreBooking" data-booking_date="<?php echo $booking['date']; ?>" data-service_id="<?php echo $booking['service_id']; ?>" data-booking_id="<?php echo $booking['booking_id']; ?>" data-extra_count="<?php echo $booking['extra_count']; ?>" data-booking_status="<?php echo $booking_status; ?>" href="#">+</a>
													<?php //} else { ?>
													<!-- <a class="editTimeBooking moreBooking" data-booking_id="<?php echo $booking['booking_id']; ?>" href="#">+</a>-->
													<?php //} ?>
												</div>
												<?php
											}
										}
										?></td><?php
									}
									?>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<div id="dialogView" title="Kalenteri" style="display: none"></div>
		<div id="dialogAddBooking" title="Lisää varaus" style="display: none"></div>
		<div id="dialogEditBooking" title="Muokkaa varausta" style="display: none"></div>
		<div id="dialogFreetime" title="Vapaat" style="display: none"></div>
		<div id="dialogEditTimeBooking" title="Muokkaa aikaa" style="display: none"></div>
		<div id="dialogEditbookingStatus" title="Muokkaa tilaa" style="display: none"></div>
		<div id="dialogExtraService" title="Lisää lisäpalvelu" style="display: none"></div>
			<div id="dialogRemoveFreetime" title="Poista vapaa aika" style="display: none">Oletko varma, että haluat poistaa valitun vapaa ajan kalenterista?</div>
			<div id="dialogItemAdd" title="<?php __('booking_service_add_title', false, true); ?>" style="display: none"></div>
			<?php
		}
	}
}
?>