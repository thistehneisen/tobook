<table cellpadding="2" cellspacing="1" class="TSBC_Table TSBC_Event TSBC_Header TEBC_Font">
	<thead>
		<tr>
			<th><?php echo $TS_LANG['front']['cart']['summary']; ?></th>
			<th style="width: 3%"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="TSBC_JS_Close">x</a></th>
		</tr>
	</thead>
</table>

<form action="" method="post" name="TSBCalBookingSummary_<?php echo $_REQUEST['cid']; ?>" class="TSBC_Form TSBC_Font">
	<?php $reservation_refid = time(); ?>
	<input type="hidden" name="reservation_refid" value="<?php echo $reservation_refid; ?>" />
	<?php
	if ($tpl['option_arr']['payment_disable'] == 'No' && $tpl['option_arr']['hide_prices'] == 'No')
	{
		if (isset($tpl['amount']['price']) && (float) $tpl['amount']['price'] > 0)
		{
			?>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['summary_price']; ?></label>
				<?php echo Util::formatCurrencySign(number_format($tpl['amount']['price'], 2, '.', ','), $tpl['option_arr']['currency']); ?>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['summary_tax']; ?></label>
				<?php echo Util::formatCurrencySign(number_format($tpl['amount']['tax'], 2, '.', ','), $tpl['option_arr']['currency']); ?>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['summary_total']; ?></label>
				<?php echo Util::formatCurrencySign(number_format($tpl['amount']['total'], 2, '.', ','), $tpl['option_arr']['currency']); ?>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['summary_deposit']; ?></label>
				<?php echo Util::formatCurrencySign(number_format($tpl['amount']['deposit'], 2, '.', ','), $tpl['option_arr']['currency']); ?>
			</p>
			<?php
		}
	}
	if (in_array($tpl['option_arr']['bf_include_name'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_name']; ?></label>
			<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_email'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_email']; ?></label>
			<?php echo isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_phone'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_phone']; ?></label>
			<?php echo isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_country'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_country']; ?></label>
			<?php
			if (isset($tpl['country_arr']) && is_array($tpl['country_arr']))
			{
				foreach ($tpl['country_arr'] as $v)
				{
					if (isset($_POST['customer_country']) && $_POST['customer_country'] == $v['id'])
					{
						echo htmlspecialchars($v['country_title']);
						break;
					}
				}
			}
			?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_city'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_city']; ?></label>
			<?php echo isset($_POST['customer_city']) ? htmlspecialchars($_POST['customer_city']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_address'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_address']; ?></label>
			<?php echo isset($_POST['customer_address']) ? htmlspecialchars($_POST['customer_address']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_zip'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_zip']; ?></label>
			<?php echo isset($_POST['customer_zip']) ? htmlspecialchars($_POST['customer_zip']) : NULL; ?>
		</p>
		<?php
	}
	if (in_array($tpl['option_arr']['bf_include_notes'], array(2, 3)))
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_notes']; ?></label>
			<?php echo isset($_POST['customer_notes']) ? nl2br(htmlspecialchars($_POST['customer_notes'])) : NULL; ?>
		</p>
		<?php
	}
	if ($tpl['option_arr']['payment_disable'] == 'No' && isset($_POST['payment_method']) && $tpl['option_arr']['hide_prices'] == 'No')
	{
		?>
		<p>
			<label class="title"><?php echo $TS_LANG['front']['bf_payment_method']; ?></label>
			<?php echo isset($_POST['payment_method']) ? @$TS_LANG['booking_payment_methods'][$_POST['payment_method']] : NULL; ?>
		</p>
		<?php
		if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
		{
			?>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['bf_cc_type']; ?></label>
				<span><?php echo @$TS_LANG['front']['bf_cc_types'][$_POST['cc_type']]; ?></span>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['bf_cc_num']; ?></label>
				<span><?php echo isset($_POST['cc_num']) ? $_POST['cc_num'] : NULL; ?></span>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['bf_cc_exp']; ?></label>
				<span><?php echo isset($_POST['cc_exp_year']) ? $_POST['cc_exp_year'] : NULL; ?>-<?php echo isset($_POST['cc_exp_month']) ? $_POST['cc_exp_month'] : NULL;?></span>
			</p>
			<p>
				<label class="title"><?php echo $TS_LANG['front']['bf_cc_code']; ?></label>
				<span><?php echo isset($_POST['cc_code']) ? $_POST['cc_code'] : NULL; ?></span>
			</p>
			<?php
		}

		if (isset($tpl['amount']['price']) && (float) $tpl['amount']['price'] > 0)
		{
			if ($tpl['option_arr']['payment_enable_paypal'] == 'Yes' && $_POST['payment_method'] == 'paypal')
			{
				?><input type="hidden" name="payment_option" value="deposit" /><?php
			} elseif ($tpl['option_arr']['payment_enable_authorize'] == 'Yes' && $_POST['payment_method'] == 'authorize') {
				?><input type="hidden" name="payment_option" value="deposit" /><?php
			} elseif ($tpl['option_arr']['payment_enable_creditcard'] == 'Yes' && $_POST['payment_method'] == 'creditcard') {
				?><input type="hidden" name="payment_option" value="deposit" /><?php
			}
		}
	}
	?>
	<p class="TSBC_Error" style="display: none"></p>
	<p>
		<label class="title">&nbsp;</label>
		<input type="button" value="<?php echo htmlspecialchars($TS_LANG['front']['button_submit']); ?>" name="TSBCalBookingSummarySubmit" class="TSBC_Button" />
		<input type="button" value="<?php echo htmlspecialchars($TS_LANG['front']['button_cancel']); ?>" name="TSBCalBookingSummaryCancel" class="TSBC_Button" />
	</p>
</form>