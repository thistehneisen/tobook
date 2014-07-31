<?php
$STORAGE = &$_SESSION[$controller->default_product][$controller->default_order];
?>
<div class="rbBox">
	<div class="rbBoxTop">
		<div class="rbBoxTopLeft"></div>
		<div class="rbBoxTopRight"></div>
	</div>
	<div class="rbBoxMiddle">
		<form action="" method="post" class="rbForm">
			<input type="hidden" name="rbBookingForm" value="1" />
			
			<?php
			if (!isset($STORAGE['table_id']))
			{
				?>
				<div class="rbLegend">
					<span class="rbLegendLeft">&nbsp;</span>
					<span class="rbLegendText"><?php echo $RB_LANG['front']['4_enquiry']; ?></span>
					<span class="rbLegendRight">&nbsp;</span>
				</div>
				<p><?php echo stripslashes(nl2br($tpl['option_arr']['enquiry'])); ?></p>
				<?php
			}
			?>
			<div class="rbLegend">
				<span class="rbLegendLeft">&nbsp;</span>
				<span class="rbLegendText"><?php echo $RB_LANG['front']['4_personal']; ?></span>
				<span class="rbLegendRight">&nbsp;</span>
			</div>
			<?php if (in_array($tpl['option_arr']['bf_include_title'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_title']; ?> <?php if ($tpl['option_arr']['bf_include_title'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<select name="c_title" class="rbSelect<?php echo ($tpl['option_arr']['bf_include_title'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_title'])); ?>">
					<option value=""><?php echo $RB_LANG['front']['4_select_title']; ?></option>
					<?php
					foreach ($RB_LANG['_titles'] as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo @$STORAGE['c_title'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_fname'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_fname']; ?> <?php if ($tpl['option_arr']['bf_include_fname'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_fname" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_fname'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_fname'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_fname'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_lname'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_lname']; ?> <?php if ($tpl['option_arr']['bf_include_lname'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_lname" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_lname'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_lname'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_lname'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_phone'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_phone']; ?> <?php if ($tpl['option_arr']['bf_include_phone'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_phone" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_phone'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_phone'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_phone'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_email'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_email']; ?> <?php if ($tpl['option_arr']['bf_include_email'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_email" class="rbText rbW320 rbEmail<?php echo ($tpl['option_arr']['bf_include_email'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_email'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_email'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_notes'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_notes']; ?> <?php if ($tpl['option_arr']['bf_include_notes'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<textarea name="c_notes" class="rbTextarea rbW320 rbH100<?php echo ($tpl['option_arr']['bf_include_notes'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_notes'])); ?>"><?php echo stripslashes(@$STORAGE['c_notes']); ?></textarea>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_company'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_company']; ?> <?php if ($tpl['option_arr']['bf_include_company'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_company" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_company'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_company'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_company'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_address'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_address']; ?> <?php if ($tpl['option_arr']['bf_include_address'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_address" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_address'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_address'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_address'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_city'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_city']; ?> <?php if ($tpl['option_arr']['bf_include_city'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_city" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_city'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_city'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_city'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_state'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_state']; ?> <?php if ($tpl['option_arr']['bf_include_state'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_state" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_state'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_state'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_state'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_zip'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_zip']; ?> <?php if ($tpl['option_arr']['bf_include_zip'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<input type="text" name="c_zip" class="rbText rbW320<?php echo ($tpl['option_arr']['bf_include_zip'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_zip'])); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['c_zip'])); ?>" />
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_country'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_country']; ?> <?php if ($tpl['option_arr']['bf_include_country'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<select name="c_country" class="rbSelect rbW328<?php echo ($tpl['option_arr']['bf_include_country'] == 3) ? ' rbRequired' : NULL; ?>" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_country'])); ?>">
					<option value=""><?php echo $RB_LANG['front']['4_select_country']; ?></option>
					<?php
					foreach ($tpl['country_arr'] as $country)
					{
						?><option value="<?php echo $country['id']; ?>"<?php echo @$STORAGE['c_country'] == $country['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($country['country_title']); ?></option><?php
					}
					?>
				</select>
			</p>
			<?php endif; ?>
			<?php
			
			if (isset($STORAGE['table_id']))
			{
				if ($tpl['option_arr']['payment_disable'] == 'No' && isset($STORAGE['table_id']) && (float) $tpl['option_arr']['booking_price'] > 0 && (float) $tpl['price_arr']['total'] > 0)
				{
					?>
					<div class="rbLegend">
						<span class="rbLegendLeft">&nbsp;</span>
						<span class="rbLegendText"><?php echo $RB_LANG['front']['4_payment']; ?></span>
						<span class="rbLegendRight">&nbsp;</span>
					</div>
					<?php
					$showDeposit = $depositFlag = false;
					if ((float) $tpl['option_arr']['booking_price'] > 0 && (float) $tpl['price_arr']['total'] > 0)
					{
						//if (in_array(@$STORAGE['payment_method'], array('paypal', 'authorize')))
						//{
							$showDeposit = true;
						//}
						$depositFlag = true;
					}
					?>
					<p id="rbDepositBox" style="display: <?php echo !$showDeposit ? 'none' : ''; ?>">
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_price']; ?>
							<br /><span style="font-weight: normal; font-size: 0.9em"><?php echo $RB_LANG['front']['4_deposit_note']; ?></span>
						</label>
						<span class="rbValue"><?php echo pjUtil::formatCurrencySign($tpl['price_arr']['total'], $tpl['option_arr']['currency']); ?></span>
						<?php if ($depositFlag) : ?><span id="rbDepositFlag"></span><?php endif; ?>
					</p>
					<?php if (in_array($tpl['option_arr']['bf_include_promo'], array(2, 3)) && isset($STORAGE['table_id'])) : ?>
					<p>
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_promo']; ?> <?php if ($tpl['option_arr']['bf_include_promo'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
						<input type="text" name="promo_code" class="rbText rbFloatLeft rbMr5 rbW100<?php echo $tpl['option_arr']['bf_include_promo'] == 3 ? ' rbRequired' : NULL; ?>" />
						<input type="button" value="" class="rbBtn rbBtnAddVoucher rbMl5" id="rbBtnAddVoucher" />
					</p>
					<p style="display: <?php echo isset($STORAGE['code']) && !empty($STORAGE['code']) ? "" : "none"; ?>">
						<label class="rbLabel rbFloatLeft rbPt5 rbAlignRight"><?php echo $RB_LANG['front']['4_promo_added']; ?></label>
						<span class="rbValue rbPromoCode rbPr5"><?php echo isset($STORAGE['code']) ? $STORAGE['code'] : NULL; ?></span>
						<span class="rbValue rbFloatLeft rbPr5 rbPromoDiscount"><?php echo @$tpl['price_arr']['discount_text']; ?></span>
						<a href="#" id="rbBtnRemoveVoucher" class="rbFloatLeft rbMt5"><?php echo $RB_LANG['front']['4_promo_remove']; ?></a>
					</p>
					<p class="rbPromoInvalid" style="display: none">
						<label class="rbLabel">&nbsp;</label>
						<span class="rbValue"><?php echo $RB_LANG['front']['4_promo_invalid']; ?></span>
					</p>
					<?php endif; ?>
					<p>
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_payment']; ?> <span class="rbRed">*</span></label>
						<select name="payment_method" class="rbSelect rbW328 rbRequired" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_payment'])); ?>">
							<option value=""><?php echo $RB_LANG['front']['4_select_payment']; ?></option>
							<?php
							foreach ($RB_LANG['_payments'] as $k => $v)
							{
								if (@$tpl['option_arr']['payment_enable_' . $k] == 'Yes')
								{
									?><option value="<?php echo $k; ?>"<?php echo @$STORAGE['payment_method'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
								}
							}
							?>
						</select>
					</p>
					<div id="rbCCData" style="display: <?php echo isset($STORAGE['payment_method']) && $STORAGE['payment_method'] == 'creditcard' ? '' : 'none'; ?>">
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_type']; ?> <span class="rbRed">*</span></label>
							<select name="cc_type" class="rbSelect" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_cc_type']); ?>">
								<option value="">---</option>
								<?php
								foreach ($RB_LANG['front']['4_cc_types'] as $k => $v)
								{
									?><option value="<?php echo $k; ?>"<?php echo @$STORAGE['cc_type'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_num']; ?> <span class="rbRed">*</span></label>
							<input type="text" name="cc_num" class="rbText" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_cc_num']); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['cc_num'])); ?>" />
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_exp']; ?> <span class="rbRed">*</span></label>
							<select name="cc_exp_month" class="rbSelect" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_cc_exp_month']); ?>">
								<option value="">---</option>
								<?php
								foreach ($RB_LANG['months_full'] as $key => $val)
								{
									?><option value="<?php echo $key;?>"<?php echo @$STORAGE['cc_exp_month'] == $key ? ' selected="selected"' : NULL; ?>><?php echo $val;?></option><?php
								}
								?>
							</select>
							<select name="cc_exp_year" class="rbSelect" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_cc_exp_year']); ?>">
								<option value="">---</option>
								<?php
								$y = (int) date('Y');
								for ($i = $y; $i <= $y + 10; $i++)
								{
									?><option value="<?php echo $i; ?>"<?php echo @$STORAGE['cc_exp_year'] == $i ? ' selected="selected"' : NULL; ?>><?php echo $i; ?></option><?php
								}
								?>
							</select>
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_code']; ?> <span class="rbRed">*</span></label>
							<input type="text" name="cc_code" class="rbText " rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_cc_code']); ?>" value="<?php echo htmlspecialchars(stripslashes(@$STORAGE['cc_code'])); ?>" />
						</p>
					</div>
					<?php
				}
				if ($tpl['option_arr']['bf_include_captcha'] == 3)
				{
					?>
					<p style="position: relative">
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_details_captcha']; ?> <span class="rbRed">*</span>:</label>
						<input type="text" name="captcha" maxlength="6" class="rbText rbW80 rbFloatLeft rbMr5 rbRequired" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_captcha']); ?>" data-err="<?php echo htmlspecialchars($RB_LANG['front']['4_v_captcha_err']); ?>" />
						<img src="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=captcha&rand=<?php echo rand(1, 9999); ?>" alt="Captcha" style="border: 1px solid rgb(224, 227, 232); margin-top: 10px;"/>
					</p>
					<?php
				}
				?>
				
				<div class="rbLegend">
					<span class="rbLegendLeft">&nbsp;</span>
					<span class="rbLegendText"><?php echo $RB_LANG['front']['4_terms']; ?></span>
					<span class="rbLegendRight">&nbsp;</span>
				</div>
				<p>
					<label class="rbLabel">&nbsp;</label>
					<label><input type="checkbox" name="c_agree" checked="checked" value="1" class="rbRequired" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_agree'])); ?>" /> <?php echo $RB_LANG['front']['4_agree']; ?></label>
				</p>
				<?php
			} else {
				if ($tpl['option_arr']['bf_include_captcha'] == 3)
				{
					?>
					<p style="position: relative">
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_details_captcha']; ?> <span class="rbRed">*</span>:</label>
						<input type="text" name="captcha" maxlength="6" class="rbText rbW80 rbFloatLeft rbMr5 rbRequired" rev="<?php echo htmlspecialchars($RB_LANG['front']['4_v_captcha']); ?>" data-err="<?php echo htmlspecialchars($RB_LANG['front']['4_v_captcha_err']); ?>" />
						<img src="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=captcha&rand=<?php echo rand(1, 9999); ?>" alt="Captcha" style="border: solid 1px #E0E3E8; vertical-align: top; position: absolute; top: 0; left: 285px; margin-top: 2px" />
					</p>
					<?php
				}
			}
			?>
			<p>
				<input type="button" value="" id="rbBtnBack" class="rbBtn rbBtnBack rbFloatLeft" />
				<input type="button" value="" id="rbBtnContinue" class="rbBtn rbBtnReview rbFloatRight" />
			</p>
			<p class="rbError" style="display: none"></p>
		</form>
	</div>
	<div class="rbBoxBottom">
		<div class="rbBoxBottomLeft"></div>
		<div class="rbBoxBottomRight"></div>
	</div>
</div>