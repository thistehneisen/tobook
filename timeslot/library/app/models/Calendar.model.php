<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Calendar model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class CalendarModel extends AppModel
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
	var $table = 'ts_booking_calendars';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'calendar_title', 'type' => 'varchar', 'default' => ':NULL')
	);
}
?>