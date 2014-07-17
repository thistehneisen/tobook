<?php
header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}
require_once ROOT_PATH . 'app/config/config.inc.php';
?>
<!doctype html>
<html>
	<head>
		<title>Restaurant Booking</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=loadCss" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=load"></script>
	</body>
</html>