<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjFormStyleModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_formstyle';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'logo', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'banner', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'color', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'background', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'font', 'type' => 'varchar', 'default' => ':NULL'),
	);

}
?>