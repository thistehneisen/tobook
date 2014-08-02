<?php
switch ($tpl['arr']['payment_method'])
{
	case 'authorize':
		include VIEWS_PATH . 'pjFront/payments/authorize.php';
		break;
	case 'paypal':
		include VIEWS_PATH . 'pjFront/payments/paypal.php';
		break;
}
?>