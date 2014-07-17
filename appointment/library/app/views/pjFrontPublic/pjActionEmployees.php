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
		<div class="asHeading">Select employee on <?php printf("%u%s %s", $j, $suffix[$S], $months[$n]); ?></div>
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
			$step = $tpl['option_arr']['o_step'] * 60;
			
			foreach ($tpl['employee_arr'] as $employee)
			{	
				?>
				<div class="asEmployee">
					<div class="asElement asElementOutline">
						<div class="asEmployeeName"><?php echo pjSanitize::html($employee['name']); ?></div>
						<div class="asEmployeeAvatar">
							<?php
							if (!empty($employee['avatar']) && is_file($employee['avatar']))
							{
								$src = PJ_INSTALL_URL . $employee['avatar'];
							} else {
								$src = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/as-nopic-gray.gif';
							}
							?>
							<img src="<?php echo $src; ?>" alt="<?php echo pjSanitize::html($employee['name']); ?>" />
							
							<div class="asEmployeeDesc">
							<?php if (!empty($employee['notes'])) { ?>
							
								<div class="asEmployeeNotes"><?php echo pjSanitize::html($employee['notes']); ?></div>
								
							<?php } if (!empty($click_on) && $acceptBookings) { ?>
							
								<div class="asEmployeeHint"><?php echo $click_on; ?></div>
							<?php } ?>
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
										if ($i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60)
										{
											$is_free = false;
											$class = "asSlotBooked";
											break;
										}
									}
									
									if ($i < time())
									{
										$is_free = false;
										$class = "asSlotUnavailable";
									}
									
									if ($i >= $employee['t_arr']['lunch_start_ts'] && $i < $employee['t_arr']['lunch_end_ts'])
									{
										$is_free = false;
										$class = "asSlotUnavailable";
									}
									?>
									<span class="asSlotBlock <?php echo $class; ?><?php echo $wideCell; ?><?php echo $acceptBookings ? NULL : ' asSlotNotAccept'; ?>" data-start_ts="<?php echo $i; ?>" data-employee_id="<?php echo $employee['id']; ?>"><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></span><?php
								}
								?>
								</div>
								<?php
							}
							?>
							<?php if ($isAvailable && $acceptBookings) : ?>
							<form action="" method="post" class="asEmployeesAppointmentForm">
								<input type="hidden" name="id" value="<?php echo (int) $employee['id']; ?>" />
								<input type="hidden" name="date" value="<?php echo pjSanitize::html($_GET['date']); ?>" />
								<input type="hidden" name="start_ts" value="" />
								<div class="asEmployeeAppointment asEmployeeAppointmentBottom">
									<input type="submit" value="<?php __('front_availability', false, true); ?>" class="asButton asSelectorButton asButtonGreen" disabled="disabled" />
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