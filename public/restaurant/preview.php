<?php
header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

if ( isset($_GET['rbpf']) && !empty($_GET['rbpf']) ) {
	$rbpf = $_GET['rbpf'];
	setcookie("rbpf", $rbpf, time()+3600, "/", "");
} else  $rbpf = isset($_COOKIE['rbpf']) ? $_COOKIE['rbpf'] : null;

define('PREFIX', $rbpf);

require_once ROOT_PATH . 'app/config/config.inc.php';
require_once ROOT_PATH . 'core/framework/pjObject.class.php';
?>
<!doctype html>
<html>
	<head>
		<title>Restaurant Booking</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=loadCss&v=<?php echo $_GET['v'] ?>" type="text/css" rel="stylesheet" />
		<?php 
		
		pjObject::import ( 'Model', array (
		'pjFormStyle',
		) );
		
		$pjFormStyleModel = new pjFormStyleModel ();
		
		$arr = $pjFormStyleModel->getAll();
		
		$style = '<style>';
		
		if ( isset ($arr[0]['color']) && !empty($arr[0]['color'])) {
			$style .= '.rbContainer{ color: '. $arr[0]['color'] .'}';
		}
		
		if ( isset ($arr[0]['background']) && !empty($arr[0]['background'])) {
			$style .= '.rbContainer{ background-color: '. $arr[0]['background'] .'}';
		}
		
		$style .= '</style>';
		
		echo $style;
		
		if ( isset ($arr[0]['font']) && !empty($arr[0]['font'])) {
			echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='. $arr[0]['font'] .'">';
			
			$arr[0]['font'] = str_replace('+', ' ', $arr[0]['font']);
			
			echo '<style>.rbContainer{ font-family: '. $arr[0]['font'] .' }</style>';
		}
		?>
	</head>
	<body>
		<script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=load&v=<?php echo $_GET['v'] ?>"></script>
	</body>
</html>
