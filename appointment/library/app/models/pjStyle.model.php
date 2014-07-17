<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjStyleModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'formstyle';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'logo', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'banner', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'color', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'background', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'font', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL'),

               
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjStyleModel($attr);
	}
}
?>