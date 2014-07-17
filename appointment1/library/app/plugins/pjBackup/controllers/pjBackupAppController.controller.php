<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjBackupAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionAdmin');
	}
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjBackup');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
	
	public function pjActionBeforeInstall()
	{
		$this->setLayout('pjActionEmpty');
		
		$result = array('code' => 200, 'info' => array());

		$field_arr = array(
			0 => array('error_titles_ARRAY_PBU01', 'error_titles_ARRAY_PBU01', 'arrays'),
			1 => array('error_titles_ARRAY_PBU02', 'error_titles_ARRAY_PBU02', 'arrays'),
			2 => array('error_titles_ARRAY_PBU03', 'error_titles_ARRAY_PBU03', 'arrays'),
			3 => array('error_titles_ARRAY_PBU04', 'error_titles_ARRAY_PBU04', 'arrays'),
			4 => array('error_bodies_ARRAY_PBU01', 'error_bodies_ARRAY_PBU01', 'arrays'),
			5 => array('error_bodies_ARRAY_PBU02', 'error_bodies_ARRAY_PBU02', 'arrays'),
			6 => array('error_bodies_ARRAY_PBU03', 'error_bodies_ARRAY_PBU03', 'arrays'),
			7 => array('error_bodies_ARRAY_PBU04', 'error_bodies_ARRAY_PBU04', 'arrays'),
			8 => array('plugin_backup_menu_backup', 'Backup plugin / Menu Backup'),
			9 => array('plugin_backup_database', 'Backup plugin / Backup database'),
			10 => array('plugin_backup_files', 'Backup plugin / Backup files'),
			11 => array('plugin_backup_btn_backup', 'Backup plugin / Backup button'),
		);
		
		$multi_arr = array(
			0 => array('Backup'),
			1 => array('Backup complete!'),
		 	2 => array('Backup failed!'),
			3 => array('Backup failed!'),
			4 => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.'),
			5 => array('All backup files have been saved.'),
			6 => array('No option was selected.'),
			7 => array('Backup not performed.'),
			8 => array('Backup'),
			9 => array('Backup database'),
			10 => array('Backup files'),
			11 => array('Backup'),
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
		
		
		return $result;
	}
}
?>