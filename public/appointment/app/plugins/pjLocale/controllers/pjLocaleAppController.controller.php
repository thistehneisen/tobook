<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
if (is_file(PJ_FRAMEWORK_PATH . 'pjPlugin.class.php'))
{
    require_once PJ_FRAMEWORK_PATH . 'pjPlugin.class.php';
}
class pjLocaleAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionAdmin');
	}
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjLocale');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
}
?>
