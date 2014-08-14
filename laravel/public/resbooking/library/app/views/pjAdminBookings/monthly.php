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
	
	if ( isset($_GET['m']) && !empty($_GET['m']) ){
		$monthly = $_GET['m'];
	
	} else $monthly = date('m');
	
	
	?>
	<div class="monthly-review">
		<h3 style="margin-top: 20px; font-size: 22px; font-weight: bold; line-height: 50px; text-align: center; border-width: 1px 1px 0px; border-style: solid; border-color: rgb(204, 204, 204);">
			<a style="float: left" class="monthly-control" href="#" data-m="<?php echo ($monthly - 1) > 0 ? $monthly - 1 : $monthly; ?>">Prev</a>
			Monthly review
			<a style="float: right" class="monthly-control" href="#" data-m="<?php echo ($monthly + 1) > 12 ? $monthly : $monthly + 1; ?>">Next</a>
		</h3>
		<table class="table report" cellpadding="0" cellspacing="0" style="margin-top: 0; ">
			<?php
			
			if (isset($tpl ['services_arr']) && count($tpl ['services_arr']) > 0) {
				
				$services = $tpl ['services_arr'];
				
				foreach ($tpl ['services_arr'] as $i => $service) {
					
					$start_time = strtotime($service['start_time']);
					$end_time = strtotime($service['end_time']);
					
					for ( $m = ($monthly - 3); $m < $monthly + 3; $m++ ) {
						
						$services[$i][$m]['r'] = 0;
						$services[$i][$m]['c'] = 0;

						if (isset($tpl ['monthly_arr']) && count($tpl ['monthly_arr']) > 0 ) {
							foreach ($tpl ['monthly_arr'] as $booking) {

								$dt = strtotime($booking['dt']);
								$_m = date('m', $dt);
								
								$dt_time = date('H:i:s', $dt);
								$dt_time = strtotime($dt_time);
								
								if ( $m < 0 ) {
									$__m = 12 + $m;
									
								} elseif ( $m > 12 ) {
									$__m = $m - 12;
								
								} else $__m = $m;
								
								if ( $start_time <= $dt_time && $dt_time <= $end_time && $__m == $_m ) {
									$services[$i][$m]['r'] += 1;
									$services[$i][$m]['c'] += $booking['people'];
								}
							}
						}
					}
				}
				
				?>
				<thead>
					<tr>
						<th class="sub"></th>
						<?php for ( $m = ($monthly - 3); $m < $monthly + 3; $m++ ) { 
							
							if ( $m < 0 ) {
								$__m = 12 + $m;
								
							} elseif ( $m > 12 ) {
								$__m = $m - 12;
							
							} else $__m = $m;
							
							$date = date('Y') . '-' . $__m . '-1 00:00:00';
							$strtotime = strtotime($date);
							?>
						<th class="sub"><?php echo date('F', $strtotime)?></th>
						<?php } ?>
					</tr>
				</thead>
			
				<tbody>
				<?php 
				$total = array();
				
				foreach ($services as $i => $service) { 
					
					for ( $m = ($monthly - 3); $m < $monthly + 3; $m++ ) {

						if ( !isset($total[$m]) ) {
							$total[$m]['r']	= $service[$m]['r'];
							$total[$m]['c']	= $service[$m]['c'];
							$total[$m]['p']	= $service[$m]['c']*$service['s_price'];
						
						} else {
							$total[$m]['r']	+= $service[$m]['r'];
							$total[$m]['c']	+= $service[$m]['c'];
							$total[$m]['p']	+= $service[$m]['c']*$service['s_price'];
						}
					}
					?>
					
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><b><?php echo $service['s_name']?></b></td>
						
						<?php for ( $m = ($monthly - 3); $m < $monthly + 3; $m++ ) { ?>
						<td>
							<?php 
							echo '<span class="lable">reservations: </span><b class="number">' . $service[$m]['r'] . '</b><br>';
							echo '<span class="lable">covers: </span><b class="number">' . $service[$m]['c'] . '</b><br>';
							echo '<span class="lable">revenue: </span><b class="number">' . $service[$m]['c']*$service['s_price'] . ' ' . $tpl['option_arr']['currency'] .'</b>';
							?>
						</td>
						<?php } ?>
					</tr>
					
				<?php } ?>
				
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><b>Totals</b></td>
						
						<?php for ( $m = ($monthly - 3); $m < $monthly + 3; $m++ ) { ?>
						<td>
							<?php 
							echo '<span class="lable">reservations: </span><b class="number">' . $total[$m]['r'] . '</b> <br>';
							echo '<span class="lable">covers: </span><b class="number">' . $total[$m]['c'] . '</b> <br>';
							echo '<span class="lable">revenue: </span><b class="number">' . $total[$m]['p'] . ' ' . $tpl['option_arr']['currency'] .'</b>';
							?>
						</td>
						<?php } ?>
					</tr>
				</tbody>
			<?php } ?>
			
		</table>
	</div>
		
	<?php
}
?>