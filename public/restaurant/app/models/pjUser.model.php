<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjUserModel extends pjAppModel
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
	var $table = 'users';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'role_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'last_login', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T')
	);
}
?>
