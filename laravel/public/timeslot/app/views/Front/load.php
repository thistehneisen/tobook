<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Front
 */
list($month, $year) = explode("-", date("n-Y"));
$position = NULL;
switch ($tpl['option_arr']['ts_booking_form_position'])
{
	case 1:
		break;
	case 2:
		break;
	case 3:
		$position = 'float: left;';
		break;
}
?>
<div id="TSBC_<?php echo $_GET['cid']; ?>" style="position: relative">
	<div id="TSBC_Calendar_<?php echo $_GET['cid']; ?>" style="<?php echo $position; ?>">
	<?php
	echo $tpl['calendar']->getMonthView($month, $year);
	if ($controller->option_arr['color_legend'] == 'Yes')
	{
		echo $tpl['calendar']->getLegend($TS_LANG);
	}
	?>
	</div>
	<div id="TSBC_Slots_<?php echo $_GET['cid']; ?>" style="<?php echo $position; ?>"></div>
	<div id="TSBC_Msg_<?php echo $_GET['cid']; ?>"></div>
	<div id="TSBC_Preload_<?php echo $_GET['cid']; ?>" style="display: none; width: <?php echo $tpl['option_arr']['calendar_width']; ?>px; height: <?php echo $tpl['option_arr']['calendar_height']; ?>px;" class="TSBC_Preload"></div>
</div>

<script type="text/javascript">
var myTSBC_<?php echo $_GET['cid']; ?> = new TSBCal();
myTSBC_<?php echo $_GET['cid']; ?>.init({
	booking_form_name: "TSBCalBookingForm_<?php echo $_GET['cid']; ?>",
	booking_form_submit_name: "TSBCalBookingFormSubmit",
	booking_form_cancel_name: "TSBCalBookingFormCancel",
	booking_form_confirm_name: "TSBCalBookingFormConfirm",
	booking_form_payment_method: "payment_method",

	timeslot_form_name: "TSBC_Form_Timeslot",
	cart_form_name: "TSBC_Form_Cart",

	category_form_name: "TSBCalCategoryForm_<?php echo $_GET['cid']; ?>",
	
	booking_summary_name: "TSBCalBookingSummary_<?php echo $_GET['cid']; ?>",
	booking_summary_submit_name: "TSBCalBookingSummarySubmit",
	booking_summary_cancel_name: "TSBCalBookingSummaryCancel",
	
	calendar_id: <?php echo $_GET['cid']; ?>,

	container_calendar: "TSBC_Calendar_<?php echo $_GET['cid']; ?>",
	container_slots: "TSBC_Slots_<?php echo $_GET['cid']; ?>",
	container_messages: "TSBC_Msg_<?php echo $_GET['cid']; ?>",
	container_price: "TSBC_PriceBox_<?php echo $_GET['cid']; ?>",
	container_basket: "TSBC_Basket_<?php echo $_GET['cid']; ?>",
	
	class_name_dates: "calendar",
	class_name_slots: "TSBC_Slot",
	class_name_month: "calendarLinkMonth",
	class_name_price: "TSBC_Price",
	slots_close: "TSBC_JS_Close",
	class_proceed: "TSBC_JS_Proceed",
	
	get_slots_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=slots",
	get_booking_form_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=bookingForm",
	get_booking_summary_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=bookingSummary",
	get_booking_captcha_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=checkCaptcha",
	get_booking_save_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=bookingSave",
	get_calendar_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=calendar",
	get_payment_form_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&action=paymentForm",
	get_cart_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=FrontCart&action=basket",
	get_add_cart_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=FrontCart&action=add",
	get_remove_cart_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=FrontCart&action=remove",
	get_update_cart_url: "<?php echo INSTALL_FOLDER; ?>index.php?controller=FrontCart&action=update",

	message_1: "<?php echo $TS_LANG['front']['msg_1']; ?>",
	message_2: "<?php echo $TS_LANG['front']['msg_2']; ?>",
	message_4: "<?php echo $TS_LANG['front']['msg_4']; ?>",
	message_5: "<?php echo $TS_LANG['front']['msg_5']; ?>",
	message_6: "<?php echo $TS_LANG['front']['msg_6']; ?>",
	message_7: "<?php echo $TS_LANG['front']['msg_7']; ?>",
	message_8: "<?php echo $TS_LANG['front']['msg_8']; ?>",
	message_9: "<?php echo $TS_LANG['front']['msg_9']; ?>",

	validation: {
		error_title: "<?php echo $TS_LANG['front']['v_err_title']; ?>",
		error_email: "<?php echo $TS_LANG['front']['v_err_email']; ?>",
		error_captcha: "<?php echo $TS_LANG['front']['v_err_captcha']; ?>",
		error_payment: "<?php echo $TS_LANG['front']['v_err_payment']; ?>",
		error_max: "<?php echo $TS_LANG['front']['v_err_max']; ?>",
		error_min: "<?php echo $TS_LANG['front']['v_err_min']; ?>"
	},

	payment: {
		paypal: "TSBC_FormPaypal_<?php echo $_GET['cid']; ?>",
		authorize: "TSBC_FormAuthorize_<?php echo $_GET['cid']; ?>"
	},

	cc_data_wrapper: "TSBC_CCData",
	cc_data_names: ["cc_type", "cc_num", "cc_exp_month", "cc_exp_year", "cc_code"],
	cc_data_flag: <?php echo $tpl['option_arr']['payment_enable_creditcard'] == 'Yes' ? 'true' : 'false'; ?>,
	
	position: <?php echo $tpl['option_arr']['ts_booking_form_position']; ?>,

	month: <?php echo date("n"); ?>,
	year: <?php echo date("Y"); ?>,
	view: 1
});
</script>
<script type="text/javascript" src="<?php echo INSTALL_FOLDER . JS_PATH; ?>jabb.sort.js"></script>