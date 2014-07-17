<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	global $as_pf;
	
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuBookings'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>"><?php __('booking_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices&amp;as_pf=<?php echo $as_pf; ?>"><?php __('plugin_invoice_menu_invoices'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCustomer&amp;as_pf=<?php echo $as_pf; ?>">Customer</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionStatistics&amp;as_pf=<?php echo $as_pf; ?>">Statistics</a></li>
		</ul>
	</div>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmCreateBooking" class="form pj-form frmBooking">
		<input type="hidden" name="booking_create" value="1" />
		<?php if ( isset($_GET['pjAdmin']) && $_GET['pjAdmin'] == 1 ) { ?>
			<input type="hidden" name="pjadmin" value="1" />
		<?php } ?>
		
		<input type="hidden" name="tmp_hash" value="<?php echo md5(uniqid(rand(), true)); ?>" />
		
		<div id="tabs">
			<ul style="display: none;">
				<li><a href="#tabs-1"><?php __('booking_tab_details'); ?></a></li>
				<li><a href="#tabs-2"><?php __('booking_tab_client'); ?></a></li>
			</ul>
			
			<div id="tabs-1">
				<?php pjUtil::printNotice(@$titles['ABK10'], @$bodies['ABK10']); ?>
				<fieldset class="fieldset white">
					<legend><?php __('booking_general'); ?></legend>
					<div class="float_left w320">
						<p>
							<label class="title"><?php __('booking_uuid'); ?>:</label>
							<input type="text" name="uuid" id="uuid" class="pj-form-field w200 required" value="<?php echo pjUtil::uuid(); ?>" />
						</p>
						<p>
							<label class="title"><?php __('booking_status'); ?>:</label>
							<select name="booking_status" id="booking_status" class="pj-form-field required w200">
								<option value=""><?php __('booking_choose'); ?></option>
								<?php
								foreach (__('booking_statuses', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>" <?php echo $k=="confirmed" ? 'selected="selected"' : null; ?>><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</p>
						<p>
							<label class="title"><?php __('booking_notes'); ?>:</label>
							<textarea name="c_notes" id="c_notes" class="pj-form-field w200 h135"></textarea>
						</p>
					</div>
					<div class="float_right w300">
						<p>
							<label class="title"><?php __('booking_name'); ?>:</label>
							<input type="text" name="c_name" id="c_name" class="pj-form-field w180 required" />
						</p>
						<p>
							<label class="title"><?php __('booking_email'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email required"></abbr></span>
								<input type="text" name="c_email" id="c_email" class="pj-form-field email w150 required" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_phone'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
								<input type="text" name="c_phone" id="c_phone" class="pj-form-field w150 required" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('booking_address_1'); ?>:</label>
							<input type="text" name="c_address_1" id="c_address_1" class="pj-form-field w180" />
						</p>
						<p>
							<label class="title"><?php __('booking_address_2'); ?>:</label>
							<input type="text" name="c_address_2" id="c_address_2" class="pj-form-field w180" />
						</p>
						<p>
							<a class="addCustomerInfo pj-button" href="#" style="display: inline-block;">Add Customer</a>
						</p>
						<div id="dialogAddCustomer" title="Customer Info" style="display: none;">
							<div class="pj-form">
								<p>
									<input type="text" name="search_customer" id="searchCustomerInfo" class="pj-form-field w300" placeholder="Search Customer"/>
									<a class="pj-button buttonSearchCustomerInfo" style="display: inline-block;" href="#">Search</a>
								</p>
								<p></p>
								<div id="boxCustomerInfo"></div>
							</div>
						</div>
					</div>
					<div class="float_right w260" style="display: none;">
						<p>
							<label class="title" style="width: 125px"><?php __('booking_payment_method'); ?>:</label>
							<select name="payment_method" id="payment_method" class="pj-form-field w120 required">
								<option value=""><?php __('booking_choose'); ?></option>
								<?php
								foreach (__('payment_methods', true) as $k => $v)
								{
									?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
								}
								?>
							</select>
						</p>
						<div class="erCC p" style="display: none">
							<label class="title" style="width: 125px"><?php __('booking_cc_type'); ?></label>
							<div class="inline_block">
								<select name="cc_type" class="pj-form-field w120">
									<option value="">---</option>
									<?php
									foreach (__('booking_cc_types', true) as $k => $v)
									{
										?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
									}
									?>
								</select>
							</div>
						</div>
						<p class="erCC" style="display: none">
							<label class="title" style="width: 125px"><?php __('booking_cc_num'); ?></label>
							<span class="inline_block">
								<input type="text" name="cc_num" id="cc_num" class="pj-form-field w120 digits" />
							</span>
						</p>
						<p class="erCC" style="display: none">
							<label class="title" style="width: 125px"><?php __('booking_cc_code'); ?></label>
							<span class="inline_block">
								<input type="text" name="cc_code" id="cc_code" class="pj-form-field w120 digits" />
							</span>
						</p>
						<p class="erCC" style="display: none">
							<label class="title" style="width: 125px"><?php __('booking_cc_exp'); ?></label>
							<span class="inline_block">
								<?php
								echo pjTime::factory()
									->attr('name', 'cc_exp_month')
									->attr('id', 'cc_exp_month')
									->attr('class', 'pj-form-field')
									->prop('format', 'M')
									->month();
								?>
								<?php
								echo pjTime::factory()
									->attr('name', 'cc_exp_year')
									->attr('id', 'cc_exp_year')
									->attr('class', 'pj-form-field')
									->prop('left', 0)
									->prop('right', 10)
									->year();
								?>
							</span>
						</p>
						<p>
							<label class="title" style="width: 125px"><?php __('booking_price'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="booking_price" id="booking_price" class="pj-form-field number w90" />
							</span>
						</p>
						<p>
							<label class="title" style="width: 125px"><?php __('booking_deposit'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="booking_deposit" id="booking_deposit" class="pj-form-field number w90" />
							</span>
						</p>
						<p>
							<label class="title" style="width: 125px"><?php __('booking_tax'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="booking_tax" id="booking_tax" class="pj-form-field number w90" />
							</span>
						</p>
						<p>
							<label class="title" style="width: 125px"><?php __('booking_total'); ?>:</label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="booking_total" id="booking_total" class="pj-form-field number w90" />
							</span>
						</p>
					</div>
					<br class="clear_both" />
					<div class="t5"></div>
					<div class="p">
						<label class="title"><?php __('booking_services'); ?>:</label>
						<div id="boxBookingItems"></div>
						
						<div id="dialogItemDelete" title="<?php __('booking_service_delete_title', false, true); ?>" style="display: none"><?php __('booking_service_delete_body'); ?></div>
						<div id="dialogItemAdd" title="<?php __('booking_service_add_title', false, true); ?>" style="display: none"></div>
					</div>
					<div id="serviceItemAdd"></div>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
						<input type="button" value="<?php __('booking_service_add', false, true); ?>" class="pj-button serviceAdd" />
						<!-- <input type="button" value="<?php __('booking_recalc', false, true); ?>" class="pj-button order-calc" />-->
					</p>
				</fieldset>
			</div>
			<div id="tabs-2" style="display: none;">
				<?php pjUtil::printNotice(@$titles['ABK11'], @$bodies['ABK11']); ?>
				<fieldset class="fieldset white">
					<legend><?php __('booking_customer'); ?></legend>
					
					<div class="float_left w300">
						<p>
							<label class="title"><?php __('booking_country'); ?>:</label>
							<select name="c_country_id" id="c_country_id" class="pj-form-field w130 custom-chosen">
								<option value=""><?php __('booking_choose'); ?></option>
								<?php
								foreach ($tpl['country_arr'] as $country)
								{
									?><option value="<?php echo $country['id']; ?>"><?php echo pjSanitize::html($country['name']); ?></option><?php
								}
								?>
							</select>
						</p>
						<p>
							<label class="title"><?php __('booking_state'); ?>:</label>
							<input type="text" name="c_state" id="c_state" class="pj-form-field w130" />
						</p>
					</div>
					<div class="float_right w330">
						<p>
							<label class="title"><?php __('booking_city'); ?>:</label>
							<input type="text" name="c_city" id="c_city" class="pj-form-field w130" />
						</p>
						<p>
							<label class="title"><?php __('booking_zip'); ?>:</label>
							<input type="text" name="c_zip" id="c_zip" class="pj-form-field w80" />
						</p>
					</div>
					<br class="clear_both" />
					
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
					</p>
				</fieldset>
				
			</div>
		</div>
	</form>
	<div id="serviceItemAddAjax" style="display: none"></div>
	<?php
}
?>