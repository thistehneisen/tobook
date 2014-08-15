<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Front
 */
switch ($tpl['arr']['payment_method'])
{
	case 'authorize':
		include VIEWS_PATH . 'Front/payments/authorize.php';
		break;
	case 'paypal':
		include VIEWS_PATH . 'Front/payments/paypal.php';
		break;
}
?>