<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjResourcesModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'resources';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL'),
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjResourcesModel($attr);
	}
}
?>