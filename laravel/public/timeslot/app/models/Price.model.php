<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Price model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class PriceModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = null;
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'ts_prices';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'start_ts', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'end_ts', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL')
	);
}
?>
