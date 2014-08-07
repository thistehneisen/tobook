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
	
	if ( isset($_GET['m']) && !empty($_GET['m']) ){
		$monthly = $_GET['m'];
	
	} else $monthly = date('m');
	
	
	?>
	<div class="monthly-review">
		<h3 style="margin-top: 20px; font-size: 22px; text-align: left; font-weight: bold; line-height: 50px; text-align: center; border-width: 1px 1px 0px; border-style: solid; border-color: rgb(204, 204, 204);">
			<div style="width: 65%; display: inline-block; text-align: center;">
				<a class="monthly-control float_left" href="#" data-m="<?php echo ($monthly - 1) > 0 ? $monthly - 1 : $monthly; ?>"><?php __('btnPrev');?></a>
				<?php __('lblMonthlyReview'); ?>
				<a class="monthly-control float_right" href="#" data-m="<?php echo ($monthly + 1) > 12 ? $monthly : $monthly + 1; ?>"><?php __('btnNext');?></a>
			</div>
			<select name="employee_id" id="employee_id" class="float_right">
				<?php 
				foreach($tpl['employees_arr'] as $e ) {
					if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 && $_GET['employee_id'] == $e['id'] ) {
						$selected = 'selected="selected"';
							
					} else $selected = '';
					?>
					<option <?php echo $selected; ?> value="<?php echo $e['id']; ?>"><?php echo $e['name']; ?></option>
				<?php } ?>
			</select>
		</h3>
		
		<table class="table report" cellpadding="0" cellspacing="0" style="margin-top: 0; ">
			<?php
			
			if (isset($tpl['employees_arr']) && count($tpl['employees_arr']) > 0) {
				
				$employee = $tpl['employee_arr'];
				for ( $m = ($monthly - 1); $m < $monthly + 1; $m++ ) {
					
					$employee[$m]['amount'] = 0;
					$employee[$m]['price'] = 0;
					$employee[$m]['booking_hours'] = 0;
					
					if (isset($tpl ['monthly_arr']) && count($tpl ['monthly_arr']) > 0 ) {
						foreach ($tpl ['monthly_arr'] as $booking) {

							$_m = date('m', $booking['start_ts']);
							
							if ( $m < 0 ) {
								$__m = 12 + $m;
								
							} elseif ( $m > 12 ) {
								$__m = $m - 12;
							
							} else $__m = $m;
							
							if ( $employee['id'] == $booking['employee_id'] && $__m == $_m ) {
								$employee[$m]['amount'] += 1;
								$employee[$m]['price'] += $booking['price'];
								$employee[$m]['booking_hours'] += $booking['total'];
							}
						}
					}
				}
				?>
				<thead>
					<tr>
						<?php for ( $m = ($monthly - 1); $m < $monthly + 1; $m++ ) { 
							
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
					
					<tr>
						
						<?php for ( $m = ($monthly - 1); $m < $monthly + 1; $m++ ) { ?>
						<td>
							<?php 
							$employee[$m]['opening_hours'] = $employee[$m]['opening_hours']/60;
							
							echo '<p>'.__('lblRevenue', true, false).' <span>'. $employee[$m]['price'] . $tpl['option_arr']['o_currency'] . '</span></p>';
							echo  '<p>'.__('lblNumOfBook', true, false).' <span>'. $employee[$m]['amount'] . '</span></p>';
							echo '<p>'.__('lblWorkingTime', true, false).' <span>'. floor($employee[$m]['opening_hours']/60) . 'h';
							echo $employee[$m]['opening_hours']%60 < 10 ? '0' . $employee[$m]['opening_hours']%60 : $employee[$m]['opening_hours']%60;
							echo '</span></p>';
							echo  '<p>'.__('lblBookingHours', true, false).' <span>'.  floor($employee[$m]['booking_hours']/60). 'h';
							echo  $employee[$m]['booking_hours']%60 < 10 ? '0' . $employee[$m]['booking_hours']%60 : $employee[$m]['booking_hours']%60;
							echo '</span></p>';
							echo '<p>'.__('lblBookingRate', true, false).' <span>';
							echo $employee[$m]['opening_hours'] > 0 ? round($employee[$m]['booking_hours']*100/$employee[$m]['opening_hours'], 2) : '0';
							echo '%</span></p>';
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