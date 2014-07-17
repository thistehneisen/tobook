<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Front.payments
 */
$url = TEST_MODE ? 'https://sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
?>
<form action="<?php echo $url; ?>" method="post" style="display: inline" name="TSBC_FormPaypal_<?php echo $_REQUEST['cid']; ?>">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="business" value="<?php echo $tpl['option_arr']['paypal_address']; ?>" />
	<input type="hidden" name="item_name" value="<?php echo htmlspecialchars(stripslashes($TS_LANG['front']['cart']['item_name'])); ?>" />
	<input type="hidden" name="item_number" value="" />
	<input type="hidden" name="invoice" value="<?php echo $tpl['arr']['id']; ?>" />
	<input type="hidden" name="custom" value="<?php echo $tpl['arr']['calendar_id']; ?>" />
	<input type="hidden" name="amount" value="<?php echo number_format($tpl['arr']['payment_option'] == 'deposit' ? $tpl['arr']['booking_deposit'] : $tpl['arr']['booking_total'], 2, '.', ''); ?>" />
	<input type="hidden" name="no_shipping" value="1" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="currency_code" value="<?php echo $tpl['option_arr']['currency']; ?>" />
    <input type="hidden" name="return" value="<?php echo $tpl['option_arr']['thank_you_page']; ?>" />
	<input type="hidden" name="notify_url" value="<?php echo INSTALL_URL; ?>index.php?controller=Front&amp;action=confirmPaypal" />
	<input type="hidden" name="lc" value="US" />
	<input type="hidden" name="rm" value="2" />
	<input type="hidden" name="bn" value="PP-BuyNowBF" />
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>