<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjPostsModel extends pjAppModel
{
	var $primaryKey = 'ID';
	
	var $table = 'posts';
	
	var $schema = array(
		array('name' => 'ID', 'type' => 'bigint', 'default' => 'None'),
		//array('name' => 'post_author', 'type' => 'bigint', 'default' => '0'),
		//array('name' => 'post_date', 'type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
		//array('name' => 'post_date_gmt', 'type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
		array('name' => 'post_content', 'type' => 'longtext', 'default' => 'None'),
		array('name' => 'post_title', 'type' => 'text', 'default' => 'None'),
		//array('name' => 'post_excerpt', 'type' => 'text', 'default' => 'None'),
		//array('name' => 'post_status', 'type' => 'varchar', 'default' => 'publish'),
		//array('name' => 'comment_status', 'type' => 'varchar', 'default' => 'open'),
		//array('name' => 'ping_status', 'type' => 'varchar', 'default' => 'open'),
		//array('name' => 'post_password', 'type' => 'varchar', 'default' => ''),
		//array('name' => 'post_name', 'type' => 'varchar', 'default' => ''),
		//array('name' => 'to_ping', 'type' => 'text', 'default' => 'None'),
		//array('name' => 'pinged', 'type' => 'text', 'default' => 'None'),
		//array('name' => 'post_modified', 'type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
		//array('name' => 'post_modified_gmt', 'type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
		//array('name' => 'post_content_filtered', 'type' => 'longtext', 'default' => 'None'),
		//array('name' => 'post_parent', 'type' => 'bigint', 'default' => '0'),
		//array('name' => 'guid', 'type' => 'varchar', 'default' => ''),
		//array('name' => 'menu_order', 'type' => 'int', 'default' => '0'),
		array('name' => 'post_type', 'type' => 'varchar', 'default' => 'post'),
		//array('name' => 'post_mime_type', 'type' => 'varchar', 'default' => ''),
		//array('name' => 'comment_count', 'type' => 'bigint', 'default' => '0'),
	);

}
?>