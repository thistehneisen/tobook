<?php
$STORAGE = &$_SESSION[$controller->default_product][$controller->default_order];
?>
<div class="rbBox">
	<div class="rbBoxTop">
		<div class="rbBoxTopLeft"></div>
		<div class="rbBoxTopRight"></div>
	</div>
	<div class="rbBoxMiddle" id="rbBoxMiddle_<?php echo $_GET['index']; ?>">
		<form action="" method="post" class="rbForm">
			<input type="hidden" name="rbSummaryForm" value="1" />
			<div class="rbLegend">
				<span class="rbLegendLeft">&nbsp;</span>
				<span class="rbLegendText"><?php echo $RB_LANG['front']['4_personal']; ?></span>
				<span class="rbLegendRight">&nbsp;</span>
			</div>
			<?php if (in_array($tpl['option_arr']['bf_include_title'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_title']; ?> <?php if ($tpl['option_arr']['bf_include_title'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo @$RB_LANG['_titles'][$STORAGE['c_title']]; ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_fname'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_fname']; ?> <?php if ($tpl['option_arr']['bf_include_fname'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_fname'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_lname'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_lname']; ?> <?php if ($tpl['option_arr']['bf_include_lname'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_lname'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_phone'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_phone']; ?> <?php if ($tpl['option_arr']['bf_include_phone'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_phone'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_email'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_email']; ?> <?php if ($tpl['option_arr']['bf_include_email'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_email'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_notes'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_notes']; ?> <?php if ($tpl['option_arr']['bf_include_notes'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo stripslashes(nl2br(@$STORAGE['c_notes'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_company'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_company']; ?> <?php if ($tpl['option_arr']['bf_include_company'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_company'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_address'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_address']; ?> <?php if ($tpl['option_arr']['bf_include_address'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_address'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_city'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_city']; ?> <?php if ($tpl['option_arr']['bf_include_city'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_city'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_state'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_state']; ?> <?php if ($tpl['option_arr']['bf_include_state'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_state'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_zip'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_zip']; ?> <?php if ($tpl['option_arr']['bf_include_zip'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['c_zip'])); ?></span>
			</p>
			<?php endif; ?>
			<?php if (in_array($tpl['option_arr']['bf_include_country'], array(2, 3))) : ?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['4_country']; ?> <?php if ($tpl['option_arr']['bf_include_country'] == 3) : ?><span class="rbRed">*</span><?php endif; ?></label>
				<span class="rbValue"><?php echo isset($tpl['country_arr']) && count($tpl['country_arr']) > 0 ? htmlspecialchars(stripslashes($tpl['country_arr']['country_title'])) : NULL; ?></span>
			</p>
			<?php endif; ?>
			<?php 
			
			if (isset($STORAGE['table_id']))
			{
				if ($tpl['option_arr']['payment_disable'] == 'No' && isset($STORAGE['table_id']) && (float) $tpl['option_arr']['booking_price'] > 0 && (float) $tpl['price_arr']['total'] > 0 )
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
					<p style="display: <?php echo isset($STORAGE['code']) && !empty($STORAGE['code']) ? "" : "none"; ?>">
						<label class="rbLabel rbFloatLeft rbPt5 rbAlignRight"><?php echo $RB_LANG['front']['4_promo_added']; ?></label>
						<span class="rbValue rbPromoCode rbPr5"><?php echo isset($STORAGE['code']) ? $STORAGE['code'] : NULL; ?></span>
						<span class="rbValue rbFloatLeft rbPr5 rbPromoDiscount"><?php echo @$tpl['price_arr']['discount_text']; ?></span>
					</p>
					<?php endif; ?>
					<p>
						<label class="rbLabel"><?php echo $RB_LANG['front']['4_payment']; ?> <span class="rbRed">*</span></label>
						<span class="rbValue"><?php echo @$RB_LANG['_payments'][$STORAGE['payment_method']]; ?></span>
					</p>
					<div id="rbCCData" style="display: <?php echo isset($STORAGE['payment_method']) && $STORAGE['payment_method'] == 'creditcard' ? '' : 'none'; ?>">
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_type']; ?> <span class="rbRed">*</span></label>
							<span class="rbValue"><?php echo @$RB_LANG['front']['4_cc_types'][$STORAGE['cc_type']]; ?></span>
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_num']; ?> <span class="rbRed">*</span></label>
							<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['cc_num'])); ?></span>
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_exp']; ?> <span class="rbRed">*</span></label>
							<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$RB_LANG['months_full'][$STORAGE['cc_exp_month']])); ?>
							<?php echo htmlspecialchars(stripslashes(@$STORAGE['cc_exp_year'])); ?></span>
						</p>
						<p>
							<label class="rbLabel"><?php echo $RB_LANG['front']['4_cc_code']; ?> <span class="rbRed">*</span></label>
							<span class="rbValue"><?php echo htmlspecialchars(stripslashes(@$STORAGE['cc_code'])); ?></span>
						</p>
					</div>
					<?php
				}
			}
			?>
			<p>
				<input type="button" value="" id="rbBtnBack" class="rbBtn rbBtnBack rbFloatLeft" />
				<input type="button" value="" id="rbBtnContinue" class="rbBtn <?php echo isset($STORAGE['table_id']) ? 'rbBtnConfirm' : 'rbBtnEnquiry'; ?> rbFloatRight" />
			</p>
			<p class="rbError" style="display: none"></p>
		</form>
	</div>
	<div class="rbBoxBottom">
		<div class="rbBoxBottomLeft"></div>
		<div class="rbBoxBottomRight"></div>
	</div>
</div>