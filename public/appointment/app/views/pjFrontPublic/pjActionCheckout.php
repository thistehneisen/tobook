<?php
if (isset($tpl['status']) && $tpl['status'] == 'OK')
{
	$FORM = @$_SESSION[$controller->defaultForm];
	include PJ_VIEWS_PATH . 'pjFrontEnd/elements/cart.php';
	?>
	<div class="asBox asServicesOuter">
		<div class="asServicesInner">
			<div class="asHeading"><?php __('front_booking_form'); ?></div>
			<div class="asSelectorElements asOverflowHidden">

				<form action="" method="post" class="asSelectorCheckoutForm">
					<input type="hidden" name="as_checkout" value="1" />

					<div class="asElement asElementOutline">

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
					<div class="asElementOutline">
						<input type="button" value="<?php __('btnCancel', false, true); ?>" class="asSelectorButton asSelectorServices asButton asButtonGray asFloatLeft" />
						<input id="submit_booking" type="submit" value="<?php __('btnContinue', false, true); ?>" class="asSelectorButton asButton asButtonGreen asFloatRight" />
					</div>

				</form>

			</div>
		</div>
	</div>
	<?php
} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
	?>
	<div class="asBox">
		<div class="asServicesInner">
			<div class="asHeading"><?php __('front_system_msg'); ?></div>
			<div class="asSelectorElements asOverflowHidden">
				<div class="asElement asElementOutline"><?php __('front_checkout_na'); ?></div>
				<div class="asElementOutline">
					<input type="button" value="<?php __('front_return_back', false, true); ?>" class="asSelectorButton asSelectorServices asButton asButtonGray" />
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
