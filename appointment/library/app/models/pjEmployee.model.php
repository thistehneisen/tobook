<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjEmployeeModel extends pjAppModel
{
	protected $primaryKey = 'id';

	protected $table = 'employees';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'phone', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'avatar', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_subscribed', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'is_subscribed_sms', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'is_active', 'type' => 'tinyint', 'default' => 1)
	);
	
	protected $validate = array(
		'rules' => array(
			'calendar_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),
			'email' => array(
				'pjActionEmail' => true,
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
			'password' => array(
				'pjActionRequired' => true,
				'pjActionNotEmpty' => true
			),
		)
	);

	public $i18n = array('name');
	
	public static function factory($attr=array())
	{
		return new pjEmployeeModel($attr);
	}
}
?>