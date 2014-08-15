<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * User model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class UserModel extends AppModel
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
	var $table = 'ts_users';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
        array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'role_id', 'type' => 'int', 'default' => ''),
		array('name' => 'session_id', 'type' => 'varchar', 'default' => ''),
		array('name' => 'username', 'type' => 'varchar', 'default' => ''),
		array('name' => 'password', 'type' => 'varchar', 'default' => ''),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'last_login', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
}
?>
