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
		
			<div class="accordion" id="accordion-category">
		<?php if ( isset($tpl['category_arr']) && count($tpl['category_arr']) > 0 && isset($tpl['service_arr']) && isset($tpl['service_arr']['data']) && !empty($tpl['service_arr']['data']) ) { 
			$i = 0;
			foreach ( $tpl['category_arr'] as $category ) {
				
				$services = array();
				 
				foreach ($tpl['service_arr']['data'] as $service) {
				
					if ( $service['category_id'] == $category['id'] ) {
						$services[] = $service;
					}
				}
				
				if ( count($services) == 0 ) continue;
				
				$i++;
			?>
				<div class="panel-group asElement asElementOutline">
		        	<div class="accordion-heading">
		          		<h3 class="asServiceName accordion-title">
		              		<?php echo $category['name']; ?>
		          		</h3>
		          		<small><?php echo $category['message']; ?></small>
		        	</div>
		        	
		        	<div class="accordion-body" style="<?php echo $i==1 ? 'display: block;' : 'display: none'; ?>">
		 				<div class="accordion-inner">
		 					<div class="accordion">
		            			<?php 
		            			list($year, $month, $day) = explode("-", $_GET['date']);
		            			foreach ($services as $service) {
		            				if ((int) $tpl['option_arr']['o_seo_url'] === 1)
		            				{
		            					$slug = sprintf("%s/%s/%s/%s-%u.html", $year, $month, $day, pjAppController::friendlyURL($service['name']), $service['id']);
		            				} else {
		            					$slug = NULL;
		            				}
		            				?>
		            				<div class="panel-group">
		            					<div class="accordion-heading">
		            						<h4 class="accordion-title"><?php echo pjSanitize::html($service['name']); ?></h4><small><?php echo pjSanitize::html($service['description']); ?></small>
		            					</div>
		            					<div class="accordion-body" style="display: none;">
							 				<div class="accordion-inner">
								 				<div class="asServiceDetails active" data-wt="0">
	            									<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
	            									<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
	            									<?php endif; ?>
	            									<div class="asElementTag asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></div>
	            									
	            								</div>
	            								<?php if ( isset($service['wtime']) && count($service['wtime']) > 0 ) {
	            									
	            									foreach ( $service['wtime'] as $wtime ) { ?>
	            										<div class="asServiceDetails" data-wt="<?php echo $wtime['id']; ?>">
	            											<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
	            											<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($wtime['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
	            											<?php endif; ?>
	            											<div class="asElementTag asServiceTime"><?php echo $wtime['length']; ?> <?php __('front_minutes'); ?></div>
	            											<div class="subDesc"><?php echo pjSanitize::html($wtime['description']); ?></div>
	            										</div>
	            									<?php 
	            									}
	            								}?>
	            								<div class="asServiceAvailability">
	            									<input type="button" data-extra_count="<?php echo $service['extra_count']; ?>" data-id="<?php echo $service['id']; ?>" data-wt="0" data-iso="<?php echo $_GET['date']; ?>" data-slug="<?php echo $slug; ?>" class="asSelectorButton asSelectorService asButton asButtonGreen" value="<?php __('front_availability', false, true); ?>" />
	            								</div>
	            								<div class="clear_both"></div>
							 				</div>
						 				</div>
						 				
            							<!-- <div class="asElement asElementOutline">-->
            								
            							<!-- </div>-->
            						</div>
            						<?php
            					}
		            			?>
		            		</div>
		  				</div>
					</div>
				</div>
		<?php } ?>
			</div>
			<div id="dialogExtraService" title="Extra Service" style="display: none"></div>
		<?php } else {
			
			if (isset($tpl['service_arr']) && isset($tpl['service_arr']['data']) && !empty($tpl['service_arr']['data']))
			{
				list($year, $month, $day) = explode("-", $_GET['date']);
				
				foreach ($tpl['service_arr']['data'] as $service)
				{
					if ((int) $tpl['option_arr']['o_seo_url'] === 1)
					{
						$slug = sprintf("%s/%s/%s/%s-%u.html", $year, $month, $day, pjAppController::friendlyURL($service['name']), $service['id']);
					} else {
						$slug = NULL;
					}
					?>
					<div class="asElement asElementOutline">
						<div class="asServiceName" data-wt="0"><?php echo pjSanitize::html($service['name']); ?></div>
						<div class="asServiceDetails active">
							<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
							<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
							<?php endif; ?>
							<div class="asElementTag asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></div>
						</div>
						<?php if ( isset($service['wtime']) && count($service['wtime']) > 0 ) {
							
							foreach ( $service['wtime'] as $wtime ) { ?>
								<div class="asServiceDetails" data-wt="<?php echo $wtime['id']; ?>">
									<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
									<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($wtime['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
									<?php endif; ?>
									<div class="asElementTag asServiceTime"><?php echo $wtime['length']; ?> <?php __('front_minutes'); ?></div>
								</div>
							<?php 
							}
						}?>
						<div class="asServiceAvailability">
							<input type="button" data-id="<?php echo $service['id']; ?>" data-wt="0" data-iso="<?php echo $_GET['date']; ?>" data-slug="<?php echo $slug; ?>" class="asSelectorButton asSelectorService asButton asButtonGreen" value="<?php __('front_availability', false, true); ?>" />
						</div>
						<div class="clear_both"></div>
						<div class="asServiceDesc"><?php echo pjSanitize::html($service['description']); ?></div>
					</div>
					<?php
				}
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