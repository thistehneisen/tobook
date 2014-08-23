<div class="asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php __('front_single_date_service'); ?></div>
		<div class="asSelectorElements asOverflowHidden">

			<form action="" method="post" class="asSelectorLayout3Form">
				<input type="hidden" name="as_single" value="1" /> 
				<input type="hidden" name="category_id" value="" /> 
				<input type="hidden" name="service_id" value="" /> 
				<input type="hidden" name="wt_id" value="0" /> 
				<input type="hidden" name="employee_id" value="" /> 
				<input type="hidden" name="date" value="" /> 
				<input type="hidden" name="start_ts" value="" /> 
				<input type="hidden" name="end_ts" value="" /> 
				<input type="hidden" name="date_start" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>" />

				<div class="asElement asElementOutline asLayout3">

					<div class="asLayout3Categories asLayout3Inner">
						<div class="heading">
							<a href="#">1. <?php __('front_single_Categories'); ?></a>
						</div>

						<div class="asBox">
							<div class="asBoxInner">
								<div class="asCategories">
									<?php if ( isset($tpl['category_arr']) ) { ?>
									<ul>
										<?php foreach ( $tpl['category_arr'] as $category ) { ?>
											
											<li><label for="category-<?php echo $category['id']; ?>" ><input type="radio" name="category" id="category-<?php echo $category['id']; ?>" value="<?php echo $category['id']; ?>" /><?php echo $category['name']; ?></label></li>
									<?php } ?>
									</ul>
									<?php } ?>
								</div>

								<div class="asServices" style="display: none">
									<?php
									if (isset ( $tpl ['categories_arr'] )) {
										
										foreach ( $tpl ['categories_arr'] as $category ) {
											?>
											<div class="asCategoryBox asCategoryBox_<?php echo $category['id']; ?>">
												<a class="asCategoriesBack" href="#"><?php echo $category['name']; ?></a>
												<ul>
													<?php foreach ($category['services'] as $service) { ?>
													<li><label for="service-<?php echo $service['id']; ?>" ><input type="radio" name="service" id="service-<?php echo $service['id']; ?>" value="<?php echo $service['id']; ?>" /><?php echo $service['name']; ?></label></li>
													<?php } ?>
												</ul>
											</div>
										<?php
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="asLayout3Employees asLayout3Inner">
						<div class="heading">
							<a href="#">2. <?php __('front_single_employee'); ?></a>
						</div>
						<div class="asBox" style="display: none; ">
							<div class="asBoxInner">
							</div>
						</div>
					</div>

					<div class="asLayout3Date asLayout3Inner">
						<div class="heading">
							<a href="#">3. </a>
						</div>
						<div class="asBox" style="display: none; ">
							<div class="asBoxInner">
							</div>
						</div>
					</div>

					<div class="asLayout3Contact asLayout3Inner">
						<div class="heading">
							<a href="#">4. </a>
						</div>
						<div class="asBox" style="display: none; ">
							<div class="asBoxInner">
								<div class="asContact">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS. </div>
							</div>
						</div>
					</div>

				</div>

				<div class="asElementOutline asAlignRight">
					<input type="submit" value="<?php __('btnBook', false, true); ?>"
						class="asSelectorButton asButton asButtonGreen"
						disabled="disabled" />
				</div>

			</form>

		</div>
	</div>
</div>