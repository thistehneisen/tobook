<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjBookingTableGroupModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_bookings_tables_group';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'tables_group_id', 'type' => 'int', 'default' => ':NULL')
	);
}
?>