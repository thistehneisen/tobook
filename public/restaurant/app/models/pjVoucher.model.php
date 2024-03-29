<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjVoucherModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'vouchers';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'discount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'valid', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'time_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'time_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'every', 'type' => 'enum', 'default' => ':NULL')
	);
	
	function getVoucher($code, $date, $time)
	{
		$sql = sprintf("SELECT *, TIME_TO_SEC(`time_from`) AS `sec_from`, TIME_TO_SEC(`time_to`) AS `sec_to` FROM `%s` WHERE `code` = '%s' AND `owner_id` = %d LIMIT 1", $this->getTable(), $code, $_SESSION['owner_id']);
		$arr = $this->execute($sql);
		if (count($arr) == 1)
		{
			$arr = $arr[0];
			$sec = pjUtil::hoursToSeconds($time);
			switch ($arr['type'])
			{
				case 'period':
					if ($date >= $arr['date_from'] && $date <= $arr['date_to'] && $sec >= $arr['sec_from'] && $sec <= $arr['sec_to'])
					{
						// OK
					} else {
						$arr = array();
					}
					break;
				case 'fixed':
					if ($arr['date_from'] == $date && $sec >= $arr['sec_from'] && $sec <= $arr['sec_to'])
					{
						// OK
					} else {
						$arr = array();
					}
					break;
				case 'recurring':
					if (date("l", strtotime($date)) == ucfirst($arr['every']) && $sec >= $arr['sec_from'] && $sec <= $arr['sec_to'])
					{
						// OK
					} else {
						$arr = array();
					}
					break;
			}
		}
		return $arr;
	}
}
?>
