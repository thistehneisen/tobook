<?php
	require_once dirname(__FILE__) . '/locale_fi.php';
	require_once dirname(__FILE__) . '/config.php';
	
	function logToFile($filename, $msg)
	{
		$fd = fopen($filename, "a");
		$str = "[" . date("Y/m/d h:i:s", time()) . "] " . $msg;
		fwrite($fd, $str . "\n");
		fclose($fd);
	}
?>
