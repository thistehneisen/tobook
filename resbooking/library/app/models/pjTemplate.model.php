<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjTemplateModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_template';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'subject', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL'),
	);

}
?>