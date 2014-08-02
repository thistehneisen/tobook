<?php
require_once LIBS_PATH . 'anet_php_sdk/AuthorizeNet.php';

$transaction_key = $tpl['option_arr']['payment_authorize_key'];
$url = TEST_MODE ? 'https://test.authorize.net/gateway/transact.dll' : 'https://secure.authorize.net/gateway/transact.dll';

$x_login        = $tpl['option_arr']['payment_authorize_mid'];
$x_amount       = number_format($tpl['arr']['total'], 2, '.', '');
$x_description  = !empty($tpl['arr']['extra_str']) ? htmlspecialchars($tpl['arr']['extra_str']) : 'Extras';
$x_fp_sequence	= md5(uniqid(rand(), true));
$x_fp_timestamp	= time();
$fingerprint    = AuthorizeNetSIM_Form::getFingerprint($x_login, $transaction_key, $x_amount, $x_fp_sequence, $x_fp_timestamp);
?>
<form method="post" action="<?php echo $url; ?>" style="display: inline" name="rbAuthorize">
	<input type="hidden" name="x_login" value="<?php echo $x_login; ?>" />
	<input type="hidden" name="x_amount" value="<?php echo $x_amount; ?>" />
	<input type="hidden" name="x_description" value="<?php echo $x_description; ?>" />
	<input type="hidden" name="x_invoice_num" value="<?php echo $tpl['arr']['id']; ?>" />
	<input type="hidden" name="x_fp_sequence" value="<?php echo $x_fp_sequence; ?>" />
	<input type="hidden" name="x_fp_timestamp" value="<?php echo $x_fp_timestamp; ?>" />
	<input type="hidden" name="x_fp_hash" value="<?php echo $fingerprint; ?>" />
	<input type="hidden" name="x_test_request" value="<?php echo TEST_MODE ? 'true' : 'false'; ?>" />
	<input type="hidden" name="x_version" value="3.1" />
	<input type="hidden" name="x_show_form" value="payment_form" />
	<input type="hidden" name="x_method" value="cc" />
	<input type="hidden" name="x_relay_response" value="TRUE" />
	<input type="hidden" name="x_relay_url" value="<?php echo INSTALL_URL; ?>index.php?controller=pjFront&action=confirmAuthorize" />
</form>