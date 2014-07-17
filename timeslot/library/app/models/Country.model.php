<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Country model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class CountryModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to null
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
	var $table = 'ts_booking_countries';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'country_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
}
?>