<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjResourcesServiceModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'resources_services';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'resources_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'service_id', 'type' => 'int', 'default' => ':NULL'),
	);
	
	protected $validate = array(
		'rules' => array(
			'employee_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),
			'service_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			)
		)
	);

	public static function factory($attr=array())
	{
		return new pjResourcesServiceModel($attr);
	}
}
?>
