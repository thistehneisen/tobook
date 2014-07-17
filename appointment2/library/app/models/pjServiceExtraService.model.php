<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjServiceExtraServiceModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'services_extra_service';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'extra_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'service_id', 'type' => 'int', 'default' => ':NULL'),
	);
	
	protected $validate = array(
		'rules' => array(
			'service_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			)
		)
	);

	public static function factory($attr=array())
	{
		return new pjServiceExtraServiceModel($attr);
	}
}
?>