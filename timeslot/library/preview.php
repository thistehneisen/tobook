<?php
/**
 * @package tsbc
 */
header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}
require_once ROOT_PATH . 'app/config/config.inc.php';
$cid = isset($_GET['cid']) && (int) $_GET['cid'] > 0 ? (int) $_GET['cid'] : NULL;
?>
<!doctype html>
<html>
	<head>
		<title>TimeSlot Booking Calendar</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&amp;action=css&amp;cid=<?php echo $cid; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo INSTALL_FOLDER; ?>app/web/css/calendar.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>app/web/js/jabb-0.2.js"></script>
		<script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>app/web/js/tsbc.js"></script>
	</head>
	<body>
	
		<script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&amp;action=load&amp;cid=<?php echo $cid; ?>"></script>
		
	</body>
</html>