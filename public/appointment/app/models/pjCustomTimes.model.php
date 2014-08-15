<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCustomTimesModel extends pjAppModel
{
	protected $primaryKey = 'id';

	protected $table = 'custom_times';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'start_lunch', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_lunch', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'is_dayoff', 'type' => 'enum', 'default' => 'F')
	);

	public static function factory($attr=array())
	{
		return new pjCustomTimesModel($attr);
	}
}
?>
