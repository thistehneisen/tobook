<?php
	require_once("../../../includes/configsettings.php");
	$link = mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD) or die("Could not connect: " . mysql_error());
	
	mysql_select_db(MYSQL_DB);

	$table_prefix = $_GET['username'].'_'."email_";
	
	$sql = file_get_contents('../config/newsletter.sql');
	
	$sql = preg_replace(
			array('/INSERT\s+INTO\s+`/', '/DROP\s+TABLE\s+IF\s+EXISTS\s+`/', '/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/'),
			array('INSERT INTO `'.$table_prefix, 'DROP TABLE IF EXISTS `'.$table_prefix, 'CREATE TABLE IF NOT EXISTS `'.$table_prefix),
			$sql);
	
	$arr = preg_split('/;(\s+)?\n/', $sql);
	
	foreach ($arr as $v)
	{
		$v = trim($v);
		if (!empty($v))
		{
			mysql_query( $v );
		}
	}
	$username = $_GET['username'];
	$_SESSION['session_loginname'] = $username;
	mysql_close($link);
	header("location: session.php?username=".$username );	
?>