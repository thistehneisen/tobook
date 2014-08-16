<?php

if (!defined("ROOT_PATH"))

{

	header("HTTP/1.1 403 Forbidden");

	exit;

}

class pjBookingStatus extends pjAppModel

{
	protected $primaryKey = 'id';

	protected $table = 'bookings_status';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'admin', 'type' => 'smallint', 'default' => ':NULL'),
	);

	public static function factory($attr=array())
	{
		return new pjBookingStatus($attr);

	}

}

?>
