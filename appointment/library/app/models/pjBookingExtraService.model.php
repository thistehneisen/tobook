<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjBookingExtraServiceModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'bookings_extra_service';
	
	protected $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'service_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'extra_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL'),
	);
	
	public static function factory($attr=array())
	{
		return new pjBookingExtraServiceModel($attr);
	}
}
?>
