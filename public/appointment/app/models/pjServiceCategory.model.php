<?php

if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}

class pjServiceCategoryModel extends pjAppModel
{
	protected $primaryKey = 'id';

	protected $table = 'services_category';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'show_front', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'order', 'type' => 'smallint', 'default' => ':NULL'),
	);

	public $i18n = array('name', 'description');

	public static function factory($attr=array())
	{
		return new pjServiceCategoryModel($attr);
	}

}

?>
