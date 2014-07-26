<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjMenuModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_menu';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'm_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'm_type', 'type' => 'enum', 'default' => 'starters'),
	);

}
?>