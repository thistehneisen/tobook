<div class="asBoxInner">
	<?php if ( isset($_GET['category_id']) && (int) $_GET['category_id'] > 0 ) { ?>
		<label class="asLabel"><?php __('front_single_service'); ?></label>
		
		<?php if ( isset($tpl['service_arr']) && count($tpl['service_arr']) > 0 ) { ?>
		<div class="asServices">
			<ul>
			<?php foreach ($tpl['service_arr'] as $service) { ?>
					<li>
						<a class="service" href="#" data-id="<?php echo $service['id']; ?>"><?php echo pjSanitize::html($service['name']); ?></a>
						<div class="asServiceMore" style="display: none; ">
							<ul class="asServiceTimes">
								<li>
									<a class="service_time" href="#" data-service_time_id="0" >
										<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
	            						<span class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></span>
	            						<?php endif; ?>
	            						<span class="asElementTag asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></span>
									</a>
								</li>
								<?php 
								if ( isset($service['service_time']) && count($service['service_time']) > 0 ) { 
									
									foreach ( $service['service_time'] as $times ) { ?>
									<li>
										<a class="service_time" href="#" data-service_time_id="<?php echo $times['id']; ?>" >
											<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
		            						<span class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($times['price'], 2), $tpl['option_arr']['o_currency']); ?></span>
		            						<?php endif; ?>
		            						<span class="asElementTag asServiceTime"><?php echo $times['length']; ?> <?php __('front_minutes'); ?></span>
										</a>
									</li>
									<?php 
									} 
								} ?>
							</ul>
							
							<?php if ( isset($service['service_extra']) && count($service['service_extra']) > 0 ) { ?> 
							<div class="asServiceExtra">
								<label class="asLabel"><?php __('front_single_extra'); ?></label>
								<ul>
									<?php foreach ( $service['service_extra'] as $extra ) { ?>
									<li>
									<label for="<?php echo $service['id']; ?>_extra_id_<?php echo $extra['id']; ?>"><input type="checkbox" id="<?php echo $service['id']; ?>_extra_id_<?php echo $extra['id']; ?>" name="extra_id[]" value="<?php echo $extra['id']; ?>" /> <?php echo $extra['name'] . ' (' . $extra['length'] . 'min)'; ?></label><br>
									</li>
									<?php } ?>
								</ul>
							</div>
							<?php }?>
						</div>
					</li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
					(!isset($_GET['employee_id']) || (int) $_GET['employee_id'] < 1) ) { ?>
		<label class="asLabel"><?php __('front_single_employee'); ?></label>
		
		<?php if ( isset($tpl['employee_arr']) && count($tpl['employee_arr']) > 0 ) { ?>
		<div class="<div class="asEmployees">
			<ul>
			<?php foreach ($tpl['employee_arr'] as $employee) { ?>
					<li><a href="#" data-id="<?php echo $employee['employee_id']; ?>"><?php echo pjSanitize::html($employee['name']); ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
						isset($_GET['employee_id']) && (int) $_GET['employee_id'] > 0 ) { ?>
		<label class="asLabel"><?php __('front_single_date'); ?></label>
		
		<?php 
		if ( isset($tpl['t_arr']) && count($tpl['t_arr']) > 0 ) { 
			$date_first = isset($_GET['date_first']) && !empty($_GET['date_first']) ? $_GET['date_first'] : date("Y-m-d");
			$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : $date_first;
			$step = $tpl['option_arr']['o_step'] * 60;
			$service_total = $tpl['service_arr']['total']*60;
			$service_before = $tpl['service_arr']['before']*60;
			?>
		<div class="asdate">
			<div class="dateStart">
				<ul>
					<?php for ( $i = 0; $i < 5; $i++ ) { ?>
						<li class="<?php echo strtotime($date) == strtotime($date_first) + $i*5*86400 ? "active" : null; ?>"><a href="#" data-date_start="<?php echo date("Y-m-d", strtotime($date_first) + $i*5*86400 ); ?>"><?php echo date("d", strtotime($date_first) + $i*5*86400 ) . ". elo - " . date("d", strtotime($date_first) + $i*5*86400 + 4*86400); ?> </a></li>
					<?php } ?>				
				</ul>
			</div>
			<table class="times" cellspacing="0">
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
					<tr class="timeInner">
						<?php foreach ($tpl['t_arr'] as $k => $t ) { 
							
							if ( $k == 0 ) {
								$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
									
							} else $isoDate = date('Y-m-d', strtotime($date . ' 00:00:00') + $k*86400);
							
							$offset = $t['end_ts'] <= $t['start_ts'] ? 86400 : 0;
							?>
							<td class="<?php echo $k == 0 ? "borderLeft" : null; ?>">
							<?php if ( !$t ) { ?>
								<div><?php __('booking_na'); ?></div>
							<?php } else { ?>
								<ul>
								<?php for ($i = $t['start_ts']; $i <= $t['end_ts'] + $offset - $step; $i += $step){ 

									$check = true;
									if ( isset($tpl['bs_arr'][$k]) && count($tpl['bs_arr'][$k]) > 0 ) {
						
										foreach ($tpl['bs_arr'][$k] as $item ) {
											
											if ($i + $service_total >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
												$check = false;
												break;
											}
										}
									}
									
									if ( isset($tpl['freetime_arr'][$k]) && count($tpl['freetime_arr'][$k]) > 0 ) {
									
										foreach ($tpl['freetime_arr'][$k] as $item ) {
											
											if ($i + $service_total >= $item['start_ts'] && $i < $item['end_ts']) {
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
									
									if ($check) {
										$class = "asAvailable" ;
										
									} else {
										$class = "asUnavailable";
									}
									?>
									<li><a class="<?php echo $class; ?>" href="#" data-date="<?php echo $isoDate; ?>" data-start_ts="<?php echo $i - $service_before ; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" ><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></li>
								<?php } ?>
								</ul>
							<?php }?>
							</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php } ?>
		
	<?php } ?>
</div>