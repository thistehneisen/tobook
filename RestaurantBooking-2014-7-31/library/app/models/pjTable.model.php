<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjTableModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_tables';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'width', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'height', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'top', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'left', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'seats', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'minimum', 'type' => 'int', 'default' => ':NULL')
	);
}
?>