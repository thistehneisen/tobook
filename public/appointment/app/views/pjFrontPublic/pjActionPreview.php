<?php
if (isset($tpl['status']) && $tpl['status'] == "OK")
{
	$FORM = @$_SESSION[$controller->defaultForm];
	?>
	<?php include PJ_VIEWS_PATH . 'pjFrontEnd/elements/cart.php'; ?>
	<div class="asBox asServicesOuter">
		<div class="asServicesInner">
			<div class="asHeading"><?php __('front_preview_form'); ?></div>
			<div class="asSelectorElements asOverflowHidden">
				
				<form action="" method="post" class="asSelectorPreviewForm">
					<input type="hidden" name="as_preview" value="1" />
					
					<div class="asElement asElementOutline">
				
						<?php if (in_array((int) $tpl['option_arr']['o_bf_name'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_name'); ?><?php if ((int) $tpl['option_arr']['o_bf_name'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_name']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_email'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_email'); ?><?php if ((int) $tpl['option_arr']['o_bf_email'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_email']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_phone'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_phone']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_address_1'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_1'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_address_1']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_address_2'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_address_2'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_2'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_address_2']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_country'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_country'); ?><?php if ((int) $tpl['option_arr']['o_bf_country'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$tpl['country_arr']['name']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_state'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_state']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_city'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_city']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_zip'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_zip']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if (in_array((int) $tpl['option_arr']['o_bf_notes'], array(2,3))) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?> <span class="asAsterisk">*</span><?php endif; ?></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['c_notes']); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if ((int) $tpl['option_arr']['o_disable_payments'] === 0) : ?>
						<div class="asRow">
							<label class="asLabel"><?php __('booking_payment_method'); ?> <span class="asAsterisk">*</span></label>
							<span class="asValue">
							<?php
							$pm = __('payment_methods', true);
							echo @$pm[$FORM['payment_method']];
							?>
							</span>
						</div>
						<div class="asRow asSelectorBank" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
							<label class="asLabel"><?php __('booking_bank_account'); ?></label>
							<span class="asValue"><?php echo pjSanitize::html($tpl['option_arr']['o_bank_account']); ?></span>
						</div>
						<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
							<label class="asLabel"><?php __('booking_cc_type'); ?> <span class="asAsterisk">*</span></label>
							<span class="asValue">
							<?php
							$ct = __('booking_cc_types', true);
							echo @$ct[$FORM['cc_type']];
							?>
							</span>
						</div>
						<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
							<label class="asLabel"><?php __('booking_cc_num'); ?> <span class="asAsterisk">*</span></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['cc_num']); ?></span>
						</div>
						<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
							<label class="asLabel"><?php __('booking_cc_code'); ?> <span class="asAsterisk">*</span></label>
							<span class="asValue"><?php echo pjSanitize::html(@$FORM['cc_code']); ?></span>
						</div>
						<div class="asRow asSelectorCCard" style="display: <?php echo @$FORM['payment_method'] != 'creditcard' ? 'none' : NULL; ?>">
							<label class="asLabel"><?php __('booking_cc_exp'); ?> <span class="asAsterisk">*</span></label>
							<span class="asValue"><?php printf("%s/%s", $FORM['cc_exp_month'], $FORM['cc_exp_year']); ?></span>
						</div>
						<?php endif; ?>
				
						<input type="hidden" name="as_validate" value="1" />
					</div>
			
					<div class="asElementOutline">
						<input type="button" value="<?php __('btnCancel', false, true); ?>" class="asSelectorButton asSelectorCheckout asButton asButtonGray asFloatLeft" />
						<input type="submit" value="<?php __('front_confirm_booking', false, true); ?>" class="asSelectorButton asButton asButtonGreen asFloatRight" />
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
				<div class="asElement asElementOutline"><?php __('front_preview_na'); ?></div>
				<div class="asElementOutline">
					<input type="button" value="<?php __('front_return_back', false, true); ?>" class="asSelectorButton asSelectorCheckout asButton asButtonGray" />
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
