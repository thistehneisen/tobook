<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjServiceTimeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'services_time';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'length', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'before', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'after', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'total', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjServiceTimeModel($attr);
	}
}
?>