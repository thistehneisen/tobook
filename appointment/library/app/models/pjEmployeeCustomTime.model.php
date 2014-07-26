<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjEmployeeCustomTime extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'employees_custom_times';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'employee_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'customtime_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjEmployeeCustomTime($attr);
	}
}
?>
