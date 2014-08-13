<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Role model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class RoleModel extends AppModel
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
	var $table = 'ts_roles';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'tinyint', 'default' => ''),
        array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'role', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
}
