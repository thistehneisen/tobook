
<?php if( isset($_GET['layout']) && (int) $_GET['layout'] == 2 ) { ?>
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
						</div>
						
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
						
					</li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
					(!isset($_GET['employee_id']) || ((int) $_GET['employee_id'] < 1 && $_GET['employee_id'] != 'all')) ) { ?>
		<label class="asLabel"><?php __('front_single_employee'); ?></label>
		
		<?php if ( isset($tpl['employee_arr']) && count($tpl['employee_arr']) > 0 ) { ?>
		<div class="asEmployees">
			<ul>
				<li><a href="#" data-id="all"><?php __('front_single_all'); ?></a></li>
			<?php foreach ($tpl['employee_arr'] as $employee) { ?>
					<li><a href="#" data-id="<?php echo $employee['employee_id']; ?>"><?php echo pjSanitize::html($employee['name']); ?></a></li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
						isset($_GET['employee_id']) && ((int) $_GET['employee_id'] > 0 || $_GET['employee_id'] == 'all')) { ?>
		<label class="asLabel"><?php __('front_single_date'); ?></label>
		
		<?php 
		if ( (isset($tpl['t_arr']) && count($tpl['t_arr']) > 0) || ( $_GET['employee_id'] == 'all' && isset($tpl['employee_ids']) && count($tpl['employee_ids']) > 0) ) { 
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
					
					<?php if ($_GET['employee_id'] == 'all') {
						
						foreach ($tpl['t_arr'] as $k => $t ) {
								
							if ( $k == 0 ) {
								$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
									
							} else $isoDate = date('Y-m-d', strtotime($date . ' 00:00:00') + $k*86400);
								
							$start_ts = 0;
							$end_ts = 0;
							foreach ($tpl['employee_ids'] as $j => $id) {
								
								if ( $t[$id] && $t[$id]['start_ts'] < $t[$id]['end_ts'] ) {
									$start_ts = $t[$id]['start_ts'];
									$end_ts = $t[$id]['end_ts'];
									
								} 
							}
							
							$offset = $end_ts <= $start_ts ? 86400 : 0;
							?>
							<td class="<?php echo $k == 0 ? "borderLeft" : null; ?>">
							
							<?php if ( $start_ts == 0 ) { ?>
								<div><?php __('booking_na'); ?></div>
								
							<?php } else { ?>
								<ul>
								<?php for ($i = $start_ts; $i <= $end_ts + $offset - $step; $i += $step){ 

									foreach ($tpl['employee_ids'] as $id) {
										$check = true;
										
										if ( !$t[$id] ) continue;
										
										if ( isset($tpl['bs_arr'][$k][$id]) && count($tpl['bs_arr'][$k][$id]) > 0 ) {
							
											foreach ($tpl['bs_arr'][$k][$id] as $item ) {
												
												if ($i + $service_total >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
													$check = false;
													break;
												}
											}
										}
										
										if ( isset($tpl['freetime_arr'][$k][$id]) && count($tpl['freetime_arr'][$k][$id]) > 0 ) {
										
											foreach ($tpl['freetime_arr'][$k][$id] as $item ) {
												
												if ($i + $service_total >= $item['start_ts'] && $i < $item['end_ts']) {
													$check = false;
													break;
												}
											}
										}
											
										if ( ($i + $service_total > $t[$id]['lunch_start_ts'] && $i < $t[$id]['lunch_end_ts']) ||
												$i + $service_total - $service_before > $t[$id]['end_ts'] + $offset ||
												$i < time() ) {
	
											$check = false;
										}
										
										
										if ( !$check ) {
											continue;
											
										} else { ?>
										<li><a class="allEmployee" href="#" data-employee_id="<?php echo $id; ?>" data-date="<?php echo $isoDate; ?>" data-start_ts="<?php echo $i - $service_before ; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" ><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></li>
										<?php } ?>
										
									<?php break; } ?>
								<?php } ?>
								</ul>
							<?php }?>
							</td>
						<?php } ?>
						
					<?php } else {
						
						foreach ($tpl['t_arr'] as $k => $t ) { 
							
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
									
									/*if ($check) {
										$class = "asAvailable" ;
										
									} else {
										$class = "asUnavailable";
									}*/
									if ($check) {
									?>
									<li><a class="<?php //echo $class; ?>" href="#" data-date="<?php echo $isoDate; ?>" data-start_ts="<?php echo $i - $service_before ; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" ><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></li>
								<?php }
								} ?>
								</ul>
							<?php }?>
							</td>
						<?php } ?>
					<?php } ?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php } ?>
		
	<?php } ?>
</div>

<?php } elseif ( isset($_GET['layout']) && (int) $_GET['layout'] == 3 ) { ?>

	<?php if ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
				(!isset($_GET['employee_id']) || ((int) $_GET['employee_id'] < 1 && $_GET['employee_id'] != 'all' ))) { ?>
	
		<?php if ( isset($tpl['employee_arr']) && count($tpl['employee_arr']) > 0 ) { ?>
		<div class="asEmployees">
			<ul>
				<li>
				<label for="employee-all" ><input type="radio" name="employee" id="employee-all" value="all" /><?php __('front_single_all'); ?></label>
				</li>
			<?php foreach ($tpl['employee_arr'] as $employee) { ?>
					<li>
					<label for="employee-<?php echo $employee['employee_id']; ?>" ><input type="radio" name="employee" id="employee-<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['employee_id']; ?>" /><?php echo $employee['name']; ?></label>
					</li>
			<?php } ?>
			</ul>
		</div>
		<?php } ?>
		
	<?php } elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
	                    isset($_GET['employee_id']) && ((int) $_GET['employee_id'] > 0 || $_GET['employee_id'] == 'all') ) { 
		$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
		$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
		?>
	    
	    <?php 

			$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d');
			$step = $tpl['option_arr']['o_step'] * 60;
			$service_total = $tpl['service_arr']['total']*60;
			$service_before = $tpl['service_arr']['before']*60;
			?>
			<div class="asdate">
				<div class="dateStart">
					<span class="float_left">
					<span class="pj-form-field-custom pj-form-field-custom-after">
						<input type="text" name="dt" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? date($tpl['option_arr']['o_date_format'], strtotime($_GET['date'])) : date($tpl['option_arr']['o_date_format']); ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</span>
				</div>
				<div class="times">
				
						<?php if ($_GET['employee_id'] == 'all') {
									
								$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
								$start_ts = 0;
								$end_ts = 0;
								foreach ($tpl['employee_ids'] as $j => $id) {
									
									if ( $tpl['t_arr'][$id] && $tpl['t_arr'][$id]['start_ts'] < $tpl['t_arr'][$id]['end_ts'] ) {
										$start_ts = $tpl['t_arr'][$id]['start_ts'];
										$end_ts = $tpl['t_arr'][$id]['end_ts'];
										
									} 
								}
								
								$offset = $end_ts <= $start_ts ? 86400 : 0;
								?>
								<div>
								
								<?php if ( $start_ts == 0 ) { ?>
									<div><?php __('booking_na'); ?></div>
									
								<?php } else { ?>
									<ul>
									<?php for ($i = $start_ts; $i <= $end_ts + $offset - $step; $i += $step){ 
	
										foreach ($tpl['employee_ids'] as $id) {
											$check = true;
											
											if ( !$tpl['t_arr'][$id] ) continue;
											
											if ( isset($tpl['bs_arr'][$id]) && count($tpl['bs_arr'][$id]) > 0 ) {
								
												foreach ($tpl['bs_arr'][$id] as $item ) {
													
													if ($i + $service_total >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
														$check = false;
														break;
													}
												}
											}
											
											if ( isset($tpl['freetime_arr'][$id]) && count($tpl['freetime_arr'][$id]) > 0 ) {
											
												foreach ($tpl['freetime_arr'][$id] as $item ) {
													
													if ($i + $service_total >= $item['start_ts'] && $i < $item['end_ts']) {
														$check = false;
														break;
													}
												}
											}
												
											if ( ($i + $service_total > $tpl['t_arr'][$id]['lunch_start_ts'] && $i < $tpl['t_arr'][$id]['lunch_end_ts']) ||
													$i + $service_total - $service_before > $tpl['t_arr'][$id]['end_ts'] + $offset ||
													$i < time() ) {
		
												$check = false;
											}
											
											
											if ( !$check ) {
												continue;
												
											} else { ?>
											<li><a class="allEmployee" href="#" data-employee_id="<?php echo $id; ?>" data-date="<?php echo $isoDate; ?>" data-start_ts="<?php echo $i - $service_before ; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" ><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></li>
											<?php } ?>
											
										<?php break; } ?>
									<?php } ?>
									</ul>
								<?php }?>
								</div>
							
						<?php } else {
							
								$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
								$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
								?>
								<div>
								<?php if ( !$tpl['t_arr'] ) { ?>
									<div><?php __('booking_na'); ?></div>
								<?php } else { ?>
									<ul>
									<?php for ($i = $tpl['t_arr']['start_ts']; $i <= $tpl['t_arr']['end_ts'] + $offset - $step; $i += $step){ 
	
										$check = true;
										if ( isset($tpl['bs_arr']) && count($tpl['bs_arr']) > 0 ) {
							
											foreach ($tpl['bs_arr'] as $item ) {
												
												if ($i + $service_total >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
													$check = false;
													break;
												}
											}
										}
										
										if ( isset($tpl['freetime_arr']) && count($tpl['freetime_arr']) > 0 ) {
										
											foreach ($tpl['freetime_arr'] as $item ) {
												
												if ($i + $service_total >= $item['start_ts'] && $i < $item['end_ts']) {
													$check = false;
													break;
												}
											}
										}
											
										if ( ($i + $service_total > $tpl['t_arr']['lunch_start_ts'] && $i < $tpl['t_arr']['lunch_end_ts']) ||
												$i + $service_total - $service_before > $tpl['t_arr']['end_ts'] + $offset ||
												$i < time() ) {
	
											$check = false;
										}
										
										if ($check) {
										?>
										<li><a href="#" data-date="<?php echo $isoDate; ?>" data-start_ts="<?php echo $i - $service_before ; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" ><?php echo date($tpl['option_arr']['o_time_format'], $i); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $i + $tpl['service_arr']['total']*60); ?></a></li>
									<?php }
									} ?>
									</ul>
								<?php }?>
								</div>
						<?php } ?>
				</div>
			</div>
	    <?php } ?>
	
<?php } ?>