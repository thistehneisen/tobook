<?php

if (!defined("ROOT_PATH"))
{

	header("HTTP/1.1 403 Forbidden");

	exit;

}

class pjExtraServiceModel extends pjAppModel

{
	protected $primaryKey = 'id';

	protected $table = 'extra_service';

	protected $schema = array(

		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'length', 'type' => 'smallint', 'default' => ':NULL'),

	);

	public static function factory($attr=array()) {
		return new pjExtraServiceModel($attr);

	}

}

?>
