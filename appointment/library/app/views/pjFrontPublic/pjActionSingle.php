<div class="asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php __('front_single_date_service'); ?></div>
		<div class="asSelectorElements asOverflowHidden">
		
			<form action="" method="post" class="asSelectorSingleForm">
				<input type="hidden" name="as_single" value="1" />
				<input type="hidden" name="category_id" value="" />
				<input type="hidden" name="service_id" value="" />
				<input type="hidden" name="wt_id" value="0" />
				<input type="hidden" name="employee_id" value="" />
				<input type="hidden" name="date" value="" />
				<input type="hidden" name="start_ts" value="" />
				<input type="hidden" name="end_ts" value="" />
				<input type="hidden" name="date_start" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>" />

				<div class="asElement asElementOutline asSingle">
				
					<div class="asSingleCategories asSingleInner">
						<div class="asBox">
							<div class="asBoxInner">
								<label class="asLabel"><?php __('front_single_Categories'); ?>:</label>
								<div class="asCategories">
									<?php if ( isset($tpl['category_arr']) ) { ?>
									<ul>
										<?php foreach ( $tpl['category_arr'] as $category ) { ?>
											<li><a href="#" data-id="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
									<?php } ?>
									</ul>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="asSingleServices asSingleInner" style="display: none">
						<div class="asBox">
						</div>
					</div>
					
					<div class="asSingleEmployees asSingleInner" style="display: none">
						<div class="asBox"></div>
					</div>
					
					<div class="asSingleDate asSingleInner" style="display: none">
						<div class="asBox"></div>
					</div>
					
				</div>
				
				<div class="asElementOutline asAlignRight">
					<input type="submit" value="<?php __('btnBook', false, true); ?>" class="asSelectorButton asButton asButtonGreen" disabled="disabled" />
				</div>
				
			</form>
		
		</div>
	</div>
</div>