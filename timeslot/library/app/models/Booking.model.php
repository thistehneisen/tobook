<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Booking model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class BookingModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = 'id';
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'ts_bookings';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_total', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'booking_deposit', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'booking_tax', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'booking_status', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'payment_method', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'payment_option', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'customer_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_phone', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_country', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'customer_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_address', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'cc_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_num', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_exp', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'processed_on', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'reminder_email', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'reminder_sms', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()')
	);
}
?>
