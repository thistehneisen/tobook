<div class="asBox asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php __('front_single_date_service'); ?></div>
		<div class="asSelectorElements asOverflowHidden">
		
			<form action="" method="post" class="asSelectorSingleForm">
				<input type="hidden" name="as_single" value="1" />
				<input type="hidden" name="category_id" value="" />
				<input type="hidden" name="service_id" value="" />
				<input type="hidden" name="employee_id" value="" />
				<input type="hidden" name="date" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>" />
				<input type="hidden" name="start_ts" value="" />
				<input type="hidden" name="end_ts" value="" />

				<div class="asElement asElementOutline">
				
					<div class="asSingleCategories">
						<div class="asBox">
							<div class="asBoxInner">
								<label class="asLabel"><?php __('front_single_Categories'); ?>:</label>
								<div class="asCategories">
									<?php if ( isset($tpl['category_arr']) ) {
										foreach ( $tpl['category_arr'] as $category ) { ?>
										<a href="#" data-id="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
									<?php } 
									} ?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="asSingleServices" style="display: none">
						<div class="asBox">
						</div>
					</div>
					
					<div class="asSingleEmployees" style="display: none">
						<div class="asBox"></div>
					</div>
					
					<div class="asSingleDate" style="display: none">
						<div class="asBox"></div>
					</div>
					
					<div class="asRow">
						<label class="asLabel"><?php __('front_single_date'); ?></label>
						<span class="asRowControl">
						<?php
						$days = __('days', true);
						$months = __('months', true);
						$suffix = __('front_day_suffix', true);
						$stack = array();
						foreach (range(1,8) as $i)
						{
							$time = strtotime("+$i day");
							$iso_date = date("Y-m-d", $time);
							
							list($w, $j, $n, $S) = explode("-", date("w-j-n-S", $time));
							
							$stack[] = array(
								'value' => $iso_date,
								'text' => $i > 1 ? sprintf("%s, %u%s %s", $days[$w], $j, $suffix[$S], $months[$n]) : __('btnTomorrow', true),
								'disabled' => (isset($tpl['next_dates'][$iso_date]) && $tpl['next_dates'][$iso_date] == 'OFF')
							);
						}
						?>
						<select name="date" class="asFormField asStretch asSelectorSingleDate">
							<?php
							foreach ($stack as $item)
							{
								?><option value="<?php echo $item['value']; ?>"<?php echo $item['value'] != $_GET['date'] || $item['disabled'] ? NULL : ' selected="selected"'; ?><?php echo !$item['disabled'] ? NULL : ' disabled="disabled"'; ?>><?php echo $item['text']; ?></option><?php
							}
							$choose = strtotime($_GET['date']) > strtotime("+8 days");
							?>
							<option value="datepicker"<?php echo !$choose ? NULL : ' selected="selected"'; ?>><?php __('front_single_choose_date'); ?></option>
						</select>
						</span>
					</div>
					
					<div class="asRow asSelectorSingleDatepicker" style="display: <?php echo !$choose ? 'none' : 'block'; ?>">
						<label class="asLabel">&nbsp;</label>
						<div class="asValue">
							<div class="asSelectorCalendar">
							<?php
							list($year, $month,) = explode("-", $_GET['date']);
							echo $tpl['calendar']->getMonthHTML((int) $month, $year);
							?>
							</div>
						</div>
					</div>
					<!-- 
					<div class="asRow">
						<label class="asLabel"><?php __('front_single_service'); ?></label>
						<span class="asRowControl">
						<select name="service_id" class="asFormField asStretch asSelectorSingleService">
							<?php
							if (isset($tpl['service_arr']) && isset($tpl['service_arr']['data']) && !empty($tpl['service_arr']['data']))
							{
								foreach ($tpl['service_arr']['data'] as $service)
								{
									?><option value="<?php echo $service['id']; ?>"><?php echo pjSanitize::html($service['name']); ?></option><?php
								}
							}
							?>
						</select>
						</span>
					</div>
					
					<?php
					if (isset($tpl['service_arr']) && isset($tpl['service_arr']['data']) && !empty($tpl['service_arr']['data']))
					{
						foreach ($tpl['service_arr']['data'] as $service)
						{
							?>
							<div class="asRow asSelectorServiceBox asSelectorService_<?php echo $service['id']; ?>" style="display: none">
								<label class="asLabel"></label>
								<div class="asValue">

									<div class="asServiceDetails">
										<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
										<div class="asElementTag asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></div>
										<?php endif; ?>
										<div class="asElementTag asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></div>
									</div>
									<div class="asServiceDesc"><?php echo nl2br(pjSanitize::html($service['description'])); ?></div>
								</div>
							</div>
							<?php
						}
					}
					?>
					-->
					<div class="asRow">
						<label class="asLabel"><?php __('front_single_time'); ?></label>
						<span class="asRowControl asSelectorSingleTimeBox">
						<?php include PJ_VIEWS_PATH . 'pjFrontEnd/pjActionGetTime.php'; ?>
						</span>
					</div>
					
					<!-- <div class="asSelectorSingleEmployee" style="display: none"></div>-->
					
				</div>
				
				<div class="asElementOutline asAlignRight">
					<input type="submit" value="<?php __('btnBook', false, true); ?>" class="asSelectorButton asButton asButtonGreen" disabled="disabled" />
				</div>
				
			</form>
		
		</div>
	</div>
</div>