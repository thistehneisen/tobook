<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
		case 3:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics&amp;rbpf=<?php echo PREFIX; ?>">Statistics</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>">Group booking</a></li>
			<!-- <li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=formstyle&amp;rbpf=<?php echo PREFIX; ?>">Form Style</a></li>-->
		</ul>
	</div>
	
	<div id="statistics">
		<table class="table report" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="sub"></th>
					<th class="sub">Yesterday</th>
					<th class="sub">Today</th>
					<th class="sub">Next 7 days</th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			
			if (isset($tpl ['services_arr']) && count($tpl ['services_arr']) > 0) {
				
				$services = $tpl ['services_arr'];
				
				$today = strtotime(date('Y-m-d') . ' 00:00:00');
				$yesterday = $today - 24*3600;
				$week = $today + 8*24*3600;
				
				foreach ($tpl ['services_arr'] as $i => $service) {
					
					$services[$i]['r_yesterday'] = 0;
					$services[$i]['c_yesterday'] = 0;
					
					$services[$i]['r_today'] = 0;
					$services[$i]['c_today'] = 0;
					
					$services[$i]['r_week'] = 0;
					$services[$i]['c_week'] = 0;
					
					$start_time = strtotime($service['start_time']);
					$end_time = strtotime($service['end_time']);
						
					if (isset($tpl ['booking_arr']) && count($tpl ['booking_arr']) > 0 ) {
							
						foreach ($tpl ['booking_arr'] as $booking) {
							$dt = strtotime($booking['dt']);
							$dt_time = date('H:i:s', $dt);
							$dt_time = strtotime($dt_time);
								
							if ( $start_time <= $dt_time && $dt_time <= $end_time ) {
									
								if ( $yesterday <= $dt && $dt < $today ) {
									$services[$i]['r_yesterday'] += 1; 
									$services[$i]['c_yesterday'] += $booking['people'];
									
								} elseif ( $dt < $today + 24*3600 ) {
									$services[$i]['r_today'] += 1;
									$services[$i]['c_today'] += $booking['people'];
									
								} elseif ( $dt < $week ) {
									$services[$i]['r_week'] += 1;
									$services[$i]['c_week'] += $booking['people'];
								}
							}
						}
					}
				}
				
				$total = array(
						'r_yesterday'	=> 0,
						'c_yesterday'	=> 0,
						'r_today'		=> 0,
						'c_today'		=> 0,
						'r_week'		=> 0,
						'c_week'		=> 0
					);
				
				foreach ($services as $i => $service) { 

					$total['r_yesterday']	+=	$service['r_yesterday'];
					$total['c_yesterday']	+=	$service['c_yesterday'];
					
					$total['r_today']		+=	$service['r_today'];
					$total['c_today']		+=	$service['c_today'];
					
					$total['r_week']		+=	$service['r_week'];
					$total['c_week']		+=	$service['c_week'];
					?>
					
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><b><?php echo $service['s_name']?></b></td>
						
						<td>
							<?php 
							echo '<b class="number">' . $service['r_yesterday'] . '</b> reservations <br>';
							echo '<b class="number">' . $service['c_yesterday'] . '</b> covers';
							?>
						</td>
						<td>
							<?php 
							echo '<b class="number">' . $service['r_today'] . '</b> reservations <br>';
							echo '<b class="number">' . $service['c_today'] . '</b> covers';
							?>
						</td>
						<td>
							<?php 
							echo '<b class="number">' . $service['r_week'] . '</b> reservations <br>';
							echo '<b class="number">' . $service['c_week'] . '</b> covers';
							?>
						</td>
					</tr>
					
				<?php } ?>
				
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><b>Totals</b></td>
						
						<td>
							<?php 
							echo '<b class="number">' . $total['r_yesterday'] . '</b> reservations <br>';
							echo '<b class="number">' . $total['c_yesterday'] . '</b> covers';
							?>
						</td>
						<td>
							<?php 
							echo '<b class="number">' . $total['r_today'] . '</b> reservations <br>';
							echo '<b class="number">' . $total['c_today'] . '</b> covers';
							?>
						</td>
						<td>
							<?php 
							echo '<b class="number">' . $total['r_week'] . '</b> reservations <br>';
							echo '<b class="number">' . $total['c_week'] . '</b> covers';
							?>
						</td>
					</tr>
					
			<?php } ?>
			
			</tbody>
			
		</table>
		
		<?php 
		$dates = array(
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 6*24*3600),
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 5*24*3600),
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 4*24*3600),
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 3*24*3600),
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 2*24*3600),
			date('Y-m-d', strtotime(date('Y-m-d') . ' 00:00:00') - 24*3600),
			date('Y-m-d')
		);
		
		$graph = array();
		
		foreach ($dates as $d ){
			$graph[$d]['covers'] = 0;
			$graph[$d]['reservations'] = 0;
			
			if ( isset($tpl ['booking_old_arr']) && count($tpl ['booking_old_arr']) > 0 ) {
				
				foreach ( $tpl ['booking_old_arr'] as $booking_old ) {
					$date = date( 'Y-m-d', (strtotime($booking_old['dt'])));
					
					if ($d == $date) {
						if ( !isset($graph[$d])) {
							$graph[$d]['covers'] = $booking_old['people'];
							$graph[$d]['reservations'] = 1;
							
						} else {
							$graph[$d]['covers'] += $booking_old['people'];
							$graph[$d]['reservations'] += 1;
						}
					}
				}
			}
		}
		
		if ( count($graph) > 0 ) {
		
			$data = array();
			foreach ($graph as $date => $value ) {
					
				$data[] = '{"period": "'. date('D d', strtotime($date)) .'", "covers": '. $value['covers'] .', "reservations": '. $value['reservations'] .'}';
			}
		}
		
		?>
		
		<div id="graph">
			<ul class="box-note">
				<li><span class="box-color-covers">color</span>Covers</li>
				<li><span class="box-color-reservations">Color</span>Reservations</li>
			</ul>
		</div>
		<div id="code-graph" class="prettyprint linenums" style="display: none; ">
			/* data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type */
			var day_data = [
				<?php echo join(', ', $data); ?>
			];
			Morris.Line({
			  element: 'graph',
			  data: day_data,
			  xkey: 'period',
			  ykeys: ['covers', 'reservations'],
			  labels: ['Covers', 'Reservations'],
			  parseTime: false
			});
		</div>
		
		<div id="calendar-month">
		</div>
		
		<div id="monthly-review">
			
		</div>
		
	</div>
	<?php
}
?>