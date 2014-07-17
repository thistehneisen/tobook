<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once dirname(__FILE__) . '/pjSmsApp.model.php';
class pjSmsModel extends pjSmsAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'plugin_sms';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'number', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'text', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()')
	);
	
	public static function factory($attr=array())
	{
		return new pjSmsModel($attr);
	}
	
	public function pjActionSetup()
	{
		$field_arr = array(
			0 => array('plugin_sms_menu_sms', 'SMS plugin / Menu SMS'),
			1 => array('plugin_sms_config', 'SMS plugin / SMS config'),
			2 => array('plugin_sms_number', 'SMS plugin / Number'),
			3 => array('plugin_sms_text', 'SMS plugin / Text'),
			4 => array('plugin_sms_status', 'SMS plugin / Status'),
			5 => array('plugin_sms_created', 'SMS plugin / Date & Time'),
			6 => array('plugin_sms_api', 'SMS plugin / API Key'),
			7 => array('error_titles_ARRAY_PSS01', 'SMS plugin / Info title', 'arrays'),
			8 => array('error_bodies_ARRAY_PSS01', 'SMS plugin / Info body', 'arrays'),
			9 => array('error_titles_ARRAY_PSS02', 'SMS plugin / API key updates info title', 'arrays'),
			10 => array('error_bodies_ARRAY_PSS02', 'SMS plugin / API key updates info body', 'arrays')
		);
		
		$multi_arr = array(
			0 => array('SMS'),
			1 => array('SMS Config'),
			2 => array('Phone number'),
			3 => array('Message'),
			4 => array('Status'),
			5 => array('Date/Time sent'),
			6 => array('API Key'),
			7 => array('SMS'),
			8 => array('To send SMS you need a valid API Key. Please, contact StivaSoft to purchase an API key.'),
			9 => array('SMS API key updated!'),
			10 => array('All changes have been saved.')
		);
		
		$pjFieldModel = pjFieldModel::factory();
		$pjMultiLangModel = pjMultiLangModel::factory();
		pjObject::import('Model', 'pjLocale:pjLocale');
		$locale_arr = pjLocaleModel::factory()->findAll()->getDataPair('id', 'id');
		
		foreach ($field_arr as $key => $field)
		{
			$insert_id = $pjFieldModel->reset()->setAttributes(array(
				'key' => $field[0],
				'type' => !isset($field[2]) ? 'backend' : $field[2],
				'label' => $field[1],
				'source' => 'plugin'
			))->insert()->getInsertId();
			if ($insert_id !== false && (int) $insert_id > 0)
			{
				foreach ($locale_arr as $locale)
				{
					$pjMultiLangModel->reset()->setAttributes(array(
						'foreign_id' => $insert_id,
						'model' => 'pjField',
						'locale' => $locale,
						'field' => 'title',
						'content' => $multi_arr[$key][0],
						'source' => 'plugin'
					))->insert();
				}
			}
		}
	}
}
?>