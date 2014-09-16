<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPostsModel extends pjAppModel
{
	protected $primaryKey = 'ID';
	
	protected $table = 'posts';
	
	protected $schema = array(
			array('name' => 'ID', 'type' => 'bigint', 'default' => 'None'),
			array('name' => 'post_content', 'type' => 'longtext', 'default' => 'None'),
			array('name' => 'post_title', 'type' => 'text', 'default' => 'None'),
			array('name' => 'post_type', 'type' => 'varchar', 'default' => 'post'),
	);
	
	public $i18n = array('name', 'description');
	
	public static function factory($attr=array())
	{
		return new pjPostsModel($attr);
	}
}
?>