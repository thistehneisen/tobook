<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjInvoiceAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionAdmin');
	}
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjInvoice');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
	
	public function pjActionBeforeInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('code' => 200, 'info' => array());
		$folders = array('app/web/invoices');
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$result['code'] = 101;
				$result['info'][] = sprintf('You need to set write permissions (chmod 777) to directory located at %s', $dir);
			}
		}
		
		return $result;
	}
	
	public function isInvoiceReady()
	{
		$reflector = new ReflectionClass('pjPlugin');
		try {
			//Try to find out 'isInvoiceReady' into parent class
			$ReflectionMethod = $reflector->getMethod('isInvoiceReady');
			return $ReflectionMethod->invoke(new pjPlugin(), 'isInvoiceReady');
		} catch (ReflectionException $e) {
			//echo $e->getMessage();
			//If failed to find it out, denied access, or not :)
			return false;
		}
	}
}
?>