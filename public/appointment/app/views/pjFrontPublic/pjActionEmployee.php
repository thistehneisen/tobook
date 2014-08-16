<?php include PJ_VIEWS_PATH . 'pjFrontEnd/elements/calendar.php'; ?>
<?php
$acceptBookings = (int) $tpl['option_arr']['o_accept_bookings'] === 1;
list($n, $j, $S) = explode("-", date("n-j-S", strtotime($_GET['date'])));
$months = __('months', true);
$suffix = __('front_day_suffix', true);
?>
<div class="asBox asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php __('front_select_services'); ?> <?php printf("%u%s %s", $j, $suffix[$S], $months[$n]); ?></div>
		<div class="asSelectorElements asOverflowHidden">
		<?php
		if (isset($tpl['service_arr']) && !empty($tpl['service_arr']))
		{
			list($year, $month, $day) = explode("-", $_GET['date']);
			
			foreach ($tpl['service_arr'] as $service)
			{
				$service_total = $service['total'] * 60;
				$service_before = $service['before'] * 60;
				
				$employee_id = isset($_GET['id']) ? $_GET['id'] : '';
				$start_ts = isset($_GET['start_ts']) ? $_GET['start_ts'] - $service_before : '';
				$end_ts = $start_ts + $service_total;
				?>
				<div class="asServiceElement">
					<div class="asElement asElementOutline">
						<div class="asServiceName"><?php echo pjSanitize::html($service['name']); ?></div>
						<div class="asServiceDesc"><?php echo pjSanitize::html($service['description']); ?></div>
						<div class="asServiceDetails">
							<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
							<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
							<?php endif; ?>
							<div class="asElementTag asServiceTime"><?php echo("min");?><?php echo $service['length']; ?> <?php __('front_minutes'); ?></div>
						</div>
						<div class="asServiceAvailability">
							<form action="" method="post" class="asEmployeeAppointmentForm">
								<input type="hidden" name="service_id" value="<?php echo (int) $service['id']; ?>" />
								<input type="hidden" name="employee_id" value="<?php echo (int) $employee_id; ?>" />
								<input type="hidden" name="date" value="<?php echo pjSanitize::html($_GET['date']); ?>" />
								<input type="hidden" name="start_ts" value="<?php echo $start_ts; ?>" />
								<input type="hidden" name="end_ts" value="<?php echo $end_ts; ?>" />
								
								<div class="asEmployeeAppointment asEmployeeAppointmentBottom">
									<input type="submit" value="<?php __('front_make_appointment', false, true); ?>" class="asButton asSelectorButton asButtonGreen"/>
									<a href="#" class="asServiceLink asSelectorServices"><?php __('btnCancel'); ?></a>
								</div>
							</form>
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