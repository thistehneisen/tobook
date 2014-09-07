<div class="asServicesOuter">
	<div class="asServicesInner">
		<div class="asHeading"><?php __('front_single_date_service'); ?></div>
		<div class="asSelectorElements asOverflowHidden">

			<form action="" method="post" class="asSelectorLayout3Form">
				<input type="hidden" name="as_layout3" value="1" />
				<input type="hidden" name="as_checkout" value="1" />
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
						<div class="heading active">
							<a href="#">1. <?php __('front_single_Categories'); ?></a>
						</div>

						<div class="asBox in">
							<div class="asBoxInner">
								<div class="asCategories in">
									<?php if ( isset($tpl['category_arr']) ) { ?>
									<ul>
										<?php foreach ( $tpl['category_arr'] as $category ) { ?>
											
											<li><label for="category-<?php echo $category['id']; ?>" ><input type="radio" name="category" id="category-<?php echo $category['id']; ?>" value="<?php echo $category['id']; ?>" /><?php echo $category['name']; ?></label></li>
									<?php } ?>
									</ul>
									<?php } ?>
								</div>

								<div class="asServices">
									<?php if (isset ( $tpl ['categories_arr'] )) {
										
										foreach ( $tpl ['categories_arr'] as $category ) { ?>
										<div class="asService asCategoryID_<?php echo $category['id']; ?>">
											<div class="asServiceBox">
												<a class="asCategoriesBack" href="#"><?php echo $category['name']; ?></a>
												<ul>
													<?php foreach ($category['services'] as $service) { ?>
													<li>
														<label for="service-<?php echo $service['id']; ?>" >
															<input type="radio" name="service" id="service-<?php echo $service['id']; ?>" value="<?php echo $service['id']; ?>" />
															<?php echo $service['name']; ?>
															<span class="asElementTag">
		            											<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
		            											<span class="asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></span>
		            											<?php endif; ?>
		            											<span class="asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></span>	
															</span>
														</label>
													</li>
													<?php } ?>
												</ul>
											</div>
											<?php foreach ($category['services'] as $service) { ?>
												<?php if ( (isset($service['service_time']) && count($service['service_time']) > 0) ||
														(isset($service['service_extra']) && count($service['service_extra']) > 0)) {
													?>
													<div class="asServiceMore asServiceID_<?php echo $service['id']; ?>">
														<a class="asServicesBack" href="#"><?php echo $service['name']; ?></a>
														<?php if (isset($service['service_time']) && count($service['service_time']) > 0) { ?>
															<ul class="asServiceTimes">
																<li>
																	<label for="service-time-0" >
																		<input type="radio" name="service_time" id="service-time-0" value="0" />
																		<span class="asElementTag">
					            											<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
					            											<span class="asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($service['price'], 2), $tpl['option_arr']['o_currency']); ?></span>
					            											<?php endif; ?>
					            											<span class="asServiceTime"><?php echo $service['length']; ?> <?php __('front_minutes'); ?></span>	
																		</span>
																	</label>
																</li>
																<?php foreach ($service['service_time'] as $times){ ?>
																<li>
																	<label for="service-time-<?php echo $times['id']; ?>" >
																		<input type="radio" name="service_time" id="service-time-<?php echo $times['id']; ?>" value="<?php echo $times['id']; ?>" />
																		<span class="asElementTag">
					            											<?php if ((int) $tpl['option_arr']['o_hide_prices'] === 0) : ?>
					            											<span class="asServicePrice"><?php echo pjUtil::formatCurrencySign(number_format($times['price'], 2), $tpl['option_arr']['o_currency']); ?></span>
					            											<?php endif; ?>
					            											<span class="asServiceTime"><?php echo $times['length']; ?> <?php __('front_minutes'); ?></span>	
																		</span>
																	</label>
																</li>
																<?php } ?>
															</ul>
														<?php } ?>
														
														<?php if (isset($service['service_extra']) && count($service['service_extra']) > 0) { ?>
															<div class="asServiceExtra">
																<label class="asLabel"><?php __('front_single_extra'); ?></label>
																<ul>
																	<?php foreach ( $service['service_extra'] as $extra ) { ?>
																	<li>
																	<label for="<?php echo $service['id']; ?>_extra_id_<?php echo $extra['id']; ?>"><input type="checkbox" id="<?php echo $service['id']; ?>_extra_id_<?php echo $extra['id']; ?>" name="extra_id[]" value="<?php echo $extra['id']; ?>" /> <?php echo $extra['name'] . ' (' . $extra['length'] . 'min)'; ?></label>
																	</li>
																	<?php } ?>
																</ul>
															</div>
														<?php } ?>
														<div class="asElementOutline"><a class="asSelectorButton asButton asButtonGreen next" href="#"><?php __('front_single_next'); ?></a></div>
													</div>
												<?php } ?>
											<?php } ?>
										</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<div class="asLayout3Employees asLayout3Inner">
						<div class="heading">
							<a href="#">2. <?php __('front_single_employee'); ?></a>
						</div>
						<div class="asBox">
							<div class="asBoxInner">
							</div>
						</div>
					</div>

					<div class="asLayout3Date asLayout3Inner">
						<div class="heading">
							<a href="#">3. <?php __('front_single_date'); ?></a>
						</div>
						<div class="asBox">
							<div class="asBoxInner">
							</div>
						</div>
					</div>

					<div class="asLayout3Contact asLayout3Inner">
						<div class="heading">
							<a href="#">4. <?php __('front_single_contact'); ?></a>
						</div>
						<div class="asBox">
							<div class="asBoxInner">
								<div class="asContact">
									<?php if (in_array((int) $tpl['option_arr']['o_bf_name'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_name'); ?><?php if ((int) $tpl['option_arr']['o_bf_name'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_name" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_name'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_name']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_email'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_email'); ?><?php if ((int) $tpl['option_arr']['o_bf_email'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_email" class="asFormField asStretch asEmail<?php echo (int) $tpl['option_arr']['o_bf_email'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_email']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_phone'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_phone" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_phone'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_phone']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_address_1'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_1'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_address_1" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_address_1'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_address_1']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_address_2'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_address_2'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_2'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_address_2" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_address_2'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_address_2']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_country'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_country'); ?><?php if ((int) $tpl['option_arr']['o_bf_country'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl">
										<select name="c_country_id" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_country'] === 3 ? ' asRequired' : NULL; ?>">
											<option value="">-- <?php __('co_select_country'); ?> --</option>
											<?php
											foreach ($tpl['country_arr'] as $country)
											{
												?><option value="<?php echo $country['id']; ?>"<?php echo $country['id'] != @$FORM['c_country_id'] ? NULL : ' selected="selected"'; ?>><?php echo pjSanitize::html($country['name']); ?></option><?php
											}
											?>
										</select>
										</span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_state'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_state" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_state'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_state']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_city'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_city" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_city'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_city']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_zip'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><input type="text" name="c_zip" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_zip'] === 3 ? ' asRequired' : NULL; ?>" value="<?php echo pjSanitize::html(@$FORM['c_zip']); ?>" /></span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_notes'], array(2,3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl"><textarea name="c_notes" class="asFormField asStretch<?php echo (int) $tpl['option_arr']['o_bf_notes'] === 3 ? ' asRequired' : NULL; ?>"><?php echo pjSanitize::html(@$FORM['c_notes']); ?></textarea></span>
									</div>
									<?php endif; ?>
			
									<?php if ((int) $tpl['option_arr']['o_disable_payments'] === 0) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_payment_method'); ?> <span class="asAsterisk">*</span></label>
										<span class="asRowControl">
											<select name="payment_method" class="asFormField asStretch asRequired">
												<option value="">-- <?php __('front_select_payment'); ?> --</option>
												<?php
												foreach (__('payment_methods', true) as $k => $v)
												{
													if ((int) $tpl['option_arr']['o_allow_' . $k] === 1)
													{
														?><option value="<?php echo $k; ?>"<?php echo @$FORM['payment_method'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
													}
												}
												?>
											</select>
										</span>
									</div>
									<div class="asRow asSelectorBank" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
										<label class="asLabel"><?php __('booking_bank_account'); ?></label>
										<span class="asValue"><?php echo pjSanitize::html($tpl['option_arr']['o_bank_account']); ?></span>
									</div>
									<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
										<label class="asLabel"><?php __('booking_cc_type'); ?> <span class="asAsterisk">*</span></label>
										<span class="asRowControl">
											<select name="cc_type" class="asFormField asStretch asRequired">
												<option value="">-- <?php __('front_select_cc_type'); ?> --</option>
												<?php
												foreach (__('booking_cc_types', true) as $k => $v)
												{
													?><option value="<?php echo $k; ?>"<?php echo @$FORM['cc_type'] != $k ? NULL : ' selected="selected"'; ?>><?php echo $v; ?></option><?php
												}
												?>
											</select>
										</span>
									</div>
									<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
										<label class="asLabel"><?php __('booking_cc_num'); ?> <span class="asAsterisk">*</span></label>
										<span class="asRowControl">
											<input type="text" name="cc_num" class="asFormField asStretch asRequired" value="<?php echo pjSanitize::html(@$FORM['cc_num']); ?>" autocomplete="off" />
										</span>
									</div>
									<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
										<label class="asLabel"><?php __('booking_cc_code'); ?> <span class="asAsterisk">*</span></label>
										<span class="asRowControl">
											<input type="text" name="cc_code" class="asFormField asStretch asRequired" value="<?php echo pjSanitize::html(@$FORM['cc_code']); ?>" autocomplete="off" />
										</span>
									</div>
									<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
										<label class="asLabel"><?php __('booking_cc_exp'); ?> <span class="asAsterisk">*</span></label>
										<span class="asRowControl">
										<?php
										$time = pjTime::factory()
											->attr('name', 'cc_exp_month')
											->attr('id', 'cc_exp_month_' . $_GET['cid'])
											->attr('class', 'asFormField asRequired')
											->prop('format', 'F');
			
										if (isset($FORM['cc_exp_month']))
										{
											$time->prop('selected', $FORM['cc_exp_month']);
										}
										echo $time->month();
										?>
			
										<?php
										$time = pjTime::factory()
											->attr('name', 'cc_exp_year')
											->attr('id', 'cc_exp_year_' . $_GET['cid'])
											->attr('class', 'asFormField asRequired')
											->prop('left', 0)
											->prop('right', 6);
			
										if (isset($FORM['cc_exp_year']))
										{
											$time->prop('selected', $FORM['cc_exp_year']);
										}
										echo $time->year();
										?>
										</span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_captcha'], array(3))) : ?>
									<div class="asRow">
										<label class="asLabel"><?php __('co_captcha'); ?><?php if ((int) $tpl['option_arr']['o_bf_captcha'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
										<span class="asRowControl">
											<input type="text" name="captcha" class="asFormField<?php echo (int) $tpl['option_arr']['o_bf_captcha'] === 3 ? ' asRequired' : NULL; ?>" maxlength="6" autocomplete="off" style="width: 90px" />
											<img alt="Captcha" src="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontEnd&action=pjActionCaptcha&rand=<?php echo rand(1000, 999999); ?>" style="vertical-align: middle" />
										</span>
									</div>
									<?php endif; ?>
			
									<?php if (in_array((int) $tpl['option_arr']['o_bf_terms'], array(3))) : ?>
									<div class="asRow">
										<label class="asLabel">&nbsp;</label>
										<span class="asRowControl" style="position: relative">
											<input type="checkbox" name="terms" id="terms_<?php echo $_GET['cid']; ?>" value="1" class="<?php echo (int) $tpl['option_arr']['o_bf_terms'] === 3 ? ' asRequired' : NULL; ?>" style="margin: 0" />
											<a href="#" for="terms_<?php echo $_GET['cid']; ?>" style="position: absolute; top: 0; left: 20px" id="toggle_term"><?php __('co_terms'); ?></a>
										</span>
									</div>
			                        <div class="asRow" id="term" style="display:none">
			                            <label class="asLabel">&nbsp;</label>
			                            <span class="asRowControl" style="position: relative">
			                                <p>Varausehdot</p>
			
			                                <p>Varaus tulee sitovasti voimaan, kun asiakas on tehnyt varauksen ja saanut siitä vahvistuksen joko puhelimitse tai kirjallisesti sähköpostitse. Palveluntarjoaja kantaa kaiken vastuun palvelun tuottamisesta ja hoitaa tarvittaessa kaiken yhteydenpidon asiakkaisiin.</p>
			
			                                <p>Peruutusehdot</p>
			
			                                <p>Varaajalla on oikeus peruutus- ja varausehtojen puitteissa peruuttaa varauksensa ilmoittamalla siitä puhelimitse vähintään 48h ennen palveluajan alkamista. Muutoin paikalle saapumatta jättämisestä voi palveluntarjoaja halutessaan periä voimassaolevan hinnastonsa mukaisen palvelukorvauksen.</p>
			                            </span>
			                        </div>
			                        <script language="text/javascript">
                        				$(document).ready(function(){
                            				$('#toggle_term').click(function(e){
                                				e.preventDefault();
                                				$('#term').slideToggle();
                            				});
                        				});
                        			</script>
			                        <?php endif; ?>
								</div>
							</div>
							<div class="asElementOutline asAlignRight">
								<input type="submit" value="<?php __('btnBook', false, true); ?>"
									class="asSelectorButton asButton asButtonGreen" />
							</div>
						</div>
					</div>

				</div>
			</form>

		</div>
	</div>
</div>