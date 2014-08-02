<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjServiceModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_service';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 's_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 's_length', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 's_price', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 's_seats', 'type' => 'smallint', 'default' => ':NULL')
	);

}
?>