<div class="asBoxInner">
	<?php if ( isset($_GET['category_id']) && (int) $_GET['category_id'] > 0 ) { ?>
		<label class="asLabel"><?php __('front_single_service'); ?></label>
		
		<?php if ( isset($tpl['service_arr']) && count($tpl['service_arr']) > 0 ) { ?>
		<div class="asServices">
			<?php foreach ($tpl['service_arr'] as $service) { ?>
					<a href="#" data-id="<?php echo $service['id']; ?>"><?php echo pjSanitize::html($service['name']); ?></a>
			<?php } ?>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
					(!isset($_GET['employee_id']) || (int) $_GET['employee_id'] < 1) ) { ?>
		<label class="asLabel"><?php __('front_single_employee'); ?></label>
		
		<?php if ( isset($tpl['employee_arr']) && count($tpl['employee_arr']) > 0 ) { ?>
		<div class="<div class="asEmployees">
			<?php foreach ($tpl['employee_arr'] as $employee) { ?>
					<a href="#" data-id="<?php echo $employee['employee_id']; ?>"><?php echo pjSanitize::html($employee['name']); ?></a>
			<?php } ?>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
						isset($_GET['employee_id']) && (int) $_GET['employee_id'] > 0 ) { ?>
		<label class="asLabel"><?php __('front_single_date'); ?></label>
		
		<?php 
		if ( isset($tpl['t_arr']) && count($tpl['t_arr']) > 0 ) { 
			$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date("Y-m-d");
			$step = $tpl['option_arr']['o_step'] * 60;
			$service_total = $tpl['service_arr']['total']*60;
			$service_before = $tpl['service_arr']['before']*60;
			?>
		<div class="asdate">
			<table class="times" width="100%">
				<thead>
					<tr>
						<?php foreach ($tpl['t_arr'] as $k => $t ) { 
							
							if ( $k == 0 ) {
								$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
							
							} else $isoDate = date('Y-m-d', strtotime($date . ' 00:00:00') + $k*86400);
							?>
							<td><span><?php echo date($tpl['option_arr']['o_date_format'], strtotime($isoDate)); ?></span></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach ($tpl['t_arr'] as $k => $t ) { 
							
							$offset = $t['end_ts'] <= $t['start_ts'] ? 86400 : 0;
							?>
							<td>
							<?php if ( !$t ) { ?>
								<div><?php __('booking_na'); ?></div>
							<?php } else {

								for ($i = $t['start_ts']; $i <= $t['end_ts'] + $offset - $step; $i += $step){ 

									$check = true;
									if ( isset($tpl['bs_arr'][$k]) && count($tpl['bs_arr'][$k]) > 0 ) {
						
										foreach ($tpl['bs_arr'][$k] as $item ) {
											
											if ($i + $service_total >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
												$check = false;
												break;
											}
										}
									}
										
									if ( ($i + $service_total > $t['lunch_start_ts'] && $i < $t['lunch_end_ts']) ||
											$i + $service_total - $service_before > $t['end_ts'] + $offset ||
											$i < time() ) {

										$check = false;
									}
									
									if ($check) { ?>
										<div><a href="#"><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></div>
									<?php } ?>
								<?php }
							}?>
							</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php } ?>
		
	<?php } ?>
</div>