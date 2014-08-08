<?php include PJ_VIEWS_PATH . 'pjFrontEnd/elements/calendar.php'; ?>
<?php
$CART = $controller->cart->getAll();
$acceptBookings = (int) $tpl['option_arr']['o_accept_bookings'] === 1;
list($n, $j, $S) = explode("-", date("n-j-S", strtotime($_GET['date'])));
$months = __('months', true);
$suffix = __('front_day_suffix', true);
?>
<div class="asBox asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php echo pjSanitize::html($tpl['service_arr']['name']); ?> <?php printf("%s %u%s %s", __('front_on', true), $j, $suffix[$S], $months[$n]); ?>
		(<a href="#" class="asSelectorServices"><?php __('front_back_services'); ?></a>)
		</div>
		<div class="asSelectorElements asOverflowHidden">
		<?php
		if (isset($tpl['employee_arr']) && !empty($tpl['employee_arr']))
		{
			$click_on = __('front_click_available', true);
			$wideCell = NULL;
			if (in_array($tpl['option_arr']['o_time_format'], array('h:i a', 'h:i A', 'g:i a', 'g:i A')))
			{
				$wideCell = " asSlotBlockWide";
			}

			$extra = array('length' => 0, 'price' => 0);
			if ( isset($_SESSION[ PREFIX . 'extra' ]) && count($_SESSION[ PREFIX . 'extra' ]) > 0 ) {
				foreach ( $_SESSION[ PREFIX . 'extra' ] as $_extra) {
					$extra['length'] += $_extra['length'];
					$extra['price'] += $_extra['price'];
				}
			}
			
			$step = $tpl['option_arr']['o_step'] * 60;
			$service_total = ($tpl['service_arr']['total'] + $extra['length']) * 60;
			$service_length = ($tpl['service_arr']['length'] + $extra['length']) * 60;
			$service_before = $tpl['service_arr']['before'] * 60;
			
			if ( isset($tpl['resources_arr']['count']) && !empty($tpl['resources_arr']['count']) ) { 
				
				$resource = array();
				foreach ($tpl['employee_arr'] as $employee) {
				
					if (!$employee['t_arr']) {
				
					} else {
				
						# Fix for 24h support
				
						$offset = $employee['t_arr']['end_ts'] <= $employee['t_arr']['start_ts'] ? 86400 : 0;
				
						for ($i = $employee['t_arr']['start_ts']; $i <= $employee['t_arr']['end_ts'] + $offset - $step; $i += $step) {
				
							$resource[$i] = 0;
									
							foreach ($tpl['resources_arr']['bs_arr'] as $item) {
									
								if ($i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60) {
									$resource[$i] = $resource[$i] + 1;
								}
							}
						}
				
					}
				
				}
			}
		
			foreach ($tpl['employee_arr'] as $employee) {
				
				$service_total = ($tpl['service_arr']['total'] + $extra['length']) * 60;
				$service_length = ($tpl['service_arr']['length'] + $extra['length']) * 60;

				if ( isset($employee['plustime']) && (int) $employee['plustime'] != 0 ) {
			
					$service_total += (int) $employee['plustime'] * 60;
					$service_length += (int) $employee['plustime'] * 60;
				}
				?>
				<div class="asEmployee">
					<div class="asElement asElementOutline">
						<div class="asEmployeeName"><?php echo pjSanitize::html($employee['name']); ?></div>
						<div class="asEmployeeAvatar">
							<?php
							if (!empty($employee['avatar']) && is_file($employee['avatar']))
							{
								$src = PJ_INSTALL_URL . $employee['avatar']; ?>
								<img src="<?php echo $src; ?>" alt="<?php echo pjSanitize::html($employee['name']); ?>" />
							<?php 
							} else {
								$src = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/as-nopic-gray.gif';
							}
							
							?>
							<?php if (!empty($employee['notes'])) { ?>
								<div class="asEmployeeNotes"><?php echo pjSanitize::html($employee['notes']); ?></div>
							<?php } ?>
							<div class="asEmployeeDetailsRight">
								<?php if (!empty($click_on) && $acceptBookings) { ?>
									<div class="asEmployeeHint"><?php echo $click_on; ?></div>
									<?php } ?>
								<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
								<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format(($tpl['service_arr']['price'] + $extra['price']), 2), $tpl['option_arr']['o_currency']); ?></div>
								<?php endif; ?>
								<div class="asElementTag asServiceTime"><?php echo isset($employee['plustime']) && (int) $employee['plustime'] != 0 ? $tpl['service_arr']['length'] + $extra['length'] + (int) $employee['plustime'] : $tpl['service_arr']['length'] + $extra['length']; ?> <?php __('front_minutes'); ?></div>
							</div>
						</div>
						
						<div class="asEmployeeInfo">
							<?php
							$isAvailable = true;
							if (!$employee['t_arr'])
							{
								$isAvailable = false;
								?><div class="asEmployeeNA"><?php __('booking_na'); ?></div><?php
							} else {
								# Fix for 24h support
								$offset = $employee['t_arr']['end_ts'] <= $employee['t_arr']['start_ts'] ? 86400 : 0;
					
								?>
								<div class="asEmployeeTimeslots">
								<?php
								for ($i = $employee['t_arr']['start_ts']; $i <= $employee['t_arr']['end_ts'] + $offset - $step; $i += $step)
								{
									$is_free = true;
									$class = "asSlotAvailable";
									
									foreach ($employee['bs_arr'] as $item)
									{
										if ($i >= $item['start_ts'] && ($i < $item['start_ts'] + $item['total'] * 60))
										{
											$is_free = false;
											//$class = "asSlotBooked";
											$class = "asSlotUnavailable";
											break;
										}
									}
									
									if ($i < time())
									{
										$is_free = false;
										$class = "asSlotUnavailable";
									}
									
									foreach ( $employee['ef_arr'] as $freetime ) {
                                        if ($i >= $freetime['start_ts'] && $i < $freetime['end_ts']) {
											$is_free = false;
											$class = "asSlotUnavailable";
											break;
										}
									}
									
									if ($i >= $employee['t_arr']['lunch_start_ts'] && $i < $employee['t_arr']['lunch_end_ts'])
									{
										$is_free = false;
										$class = "asSlotUnavailable";
									}
									if ($is_free)
									{

										foreach ($employee['bs_arr'] as $item)
										{
											if ($i + $service_total - $service_before > $item['start_ts'] && $i <= $item['start_ts'])
											{
												// before booking
												//$class = "asSlotUnavailable";
												break;
											}
										}
										if ($i + $service_total - $service_before > $employee['t_arr']['end_ts'] + $offset)
										{
											// end of working day
											$class = "asSlotUnavailable";
										}
										$key = sprintf("%u|%s|%u|%s|%s|%u", $_GET['cid'], $_GET['date'], $_GET['id'], $i - $service_before, $i + $service_total - $service_before, $employee['employee_id']);
										if (array_key_exists($key, $CART))
										{
											//$class = "asSlotAvailable asSlotSelected";
											$class = "asSlotCart";
										}
									
										if ( !empty($tpl['resources_arr']['count']) && 
												$tpl['resources_arr']['count'] > 0 &&
												isset($resource[$i]) &&
												$tpl['resources_arr']['count'] <= $resource[$i] ) {
											
											$class = "asSlotUnavailable";
										}
										
									}
									?><span class="asSlotBlock <?php echo $class; ?><?php echo $wideCell; ?><?php echo $acceptBookings ? NULL : ' asSlotNotAccept'; ?>" data-end="<?php echo date($tpl['option_arr']['o_time_format'], $i + $service_length); ?>" data-start_ts="<?php echo $i - $service_before; ?>" data-end_ts="<?php echo $i + $service_total - $service_before; ?>" data-service_id="<?php echo $employee['service_id']; ?>" data-employee_id="<?php echo $employee['employee_id']; ?>"><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></span><?php
								}
								?>
								</div>
								<?php
							}
							?>
							<?php if ($isAvailable && $acceptBookings) : ?>
							<form action="" method="post" class="asSelectorAppointmentForm">
								<input type="hidden" name="service_id" value="<?php echo (int) $employee['service_id']; ?>" />
								<input type="hidden" name="wt_id" value="<?php echo isset($tpl['service_arr']['wt_id']) && $tpl['service_arr']['wt_id'] > 0 ? $tpl['service_arr']['wt_id'] : '0'; ?>" />
								<input type="hidden" name="employee_id" value="<?php echo (int) $employee['employee_id']; ?>" />
								<input type="hidden" name="date" value="<?php echo pjSanitize::html($_GET['date']); ?>" />
								<input type="hidden" name="start_ts" value="" />
								<input type="hidden" name="end_ts" value="" />
								<div class="asEmployeeAppointment asEmployeeAppointmentTop">
									<?php
									if (isset($_GET['employee_id']) && (int) $_GET['employee_id'] === (int) $employee['employee_id'])
									{
										?><div class="asEmployeeHint"><?php __('front_cart_done'); ?> <a href="#" class="asServiceLink asSelectorCheckout"><?php __('front_checkout'); ?></a></div><?php
									}
									?>
									<input type="submit" value="<?php __('front_make_appointment', false, true); ?>" class="asButton asSelectorButton asButtonGreen" disabled="disabled" />
									<a href="#" class="asServiceLink asSelectorServices"><?php __('btnCancel'); ?></a>
								</div>
								<div class="asElementTag asEmployeeTime" style="display: none">
									<div class="asEmployeeTimeLabel"><?php __('front_start_time'); ?>:</div>
									<div class="asEmployeeTimeValue"></div>
								</div>
								<div class="asElementTag asEmployeeTime" style="display: none">
									<div class="asEmployeeTimeLabel"><?php __('front_end_time'); ?>:</div>
									<div class="asEmployeeTimeValue"></div>
								</div>
								<div class="asEmployeeAppointment asEmployeeAppointmentBottom">
									<?php
									if (isset($_GET['employee_id']) && (int) $_GET['employee_id'] === (int) $employee['employee_id'])
									{
										?><div class="asEmployeeHint"><?php __('front_cart_done'); ?> <a href="#" class="asServiceLink asSelectorCheckout"><?php __('front_checkout'); ?></a></div><?php
									}
									?>
									<input type="submit" value="<?php __('front_make_appointment', false, true); ?>" class="asButton asSelectorButton asButtonGreen" disabled="disabled" />
									<a href="#" class="asServiceLink asSelectorServices"><?php __('btnCancel'); ?></a>
								</div>
							</form>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
		</div>
	</div>
</div>

<?php
if ($acceptBookings)
{
	include PJ_VIEWS_PATH . 'pjFrontEnd/elements/cart.php';
}
?>
