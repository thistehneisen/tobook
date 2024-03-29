<?php
require_once realpath(__DIR__.'/../../Bridge.php');
$varaaDb = Bridge::dbConfig();

//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
$isFront = isset($_GET['controller']) && $_GET['controller'] === 'pjFront';

if (!Bridge::hasOwnerId() && !$isFront) {
	@session_destroy();
	echo <<< JS
<script>
window.parent.location = '/business/auth/login';
</script>
JS;
	exit;
}

@session_start();
if (!isset($_SERVER['SERVER_ADDR']) && function_exists('gethostbyname'))
{
	$_SERVER['SERVER_ADDR'] = gethostbyname($_SERVER['SERVER_NAME']);
}

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
require ROOT_PATH . 'oneapi.php';
if (!isset($_GET['controller']) || empty($_GET['controller']))
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . INSTALL_URL . basename($_SERVER['PHP_SELF'])."?controller=pjAdmin&action=index");
	exit;
}

if (isset($_GET['controller']))
{
	if (!is_file(CONTROLLERS_PATH . $_GET['controller'] . '.controller.php'))
	{
		echo 'controller not found';
		exit;
	}

	require_once CONTROLLERS_PATH . $_GET['controller'] . '.controller.php';
	if (class_exists($_GET['controller']))
	{
		$controller = new $_GET['controller'];

		if (is_object($controller))
		{
			$controller->setDefaultProduct('RestaurantBooking_' . PREFIX );
			$tpl = &$controller->tpl;

			if (isset($_GET['action']))
			{
				$action = $_GET['action'];
				if (method_exists($controller, $action))
				{
					$controller->beforeFilter();
					parse_str($_SERVER['QUERY_STRING'], $output);
					unset($output['controller']);
					unset($output['action']);
					$output = array_map("urlencode", $output);
					$params = count($output) > 0 ? "'" . join("','", $output) . "'" : '';
					$str = '$controller->$action('.$params.');';
					eval($str);
					$controller->afterFilter();
					unset($str);
					unset($params);
					$controller->beforeRender();
					$content_tpl = VIEWS_PATH . $_GET['controller'] . '/' . $action . '.php';
				} else {
					echo 'method didn\'t exists';
					exit;
				}
			} else {
				$_GET['action'] = 'index';

				$controller->beforeFilter();
				$controller->index();
				$controller->afterFilter();
				$controller->beforeRender();
				$content_tpl = VIEWS_PATH . $_GET['controller'] . '/index.php';
			}

			if (!is_file($content_tpl))
			{
				echo 'template not found';
				exit;
			}

			# Language
			require ROOT_PATH . 'app/locale/'. $controller->getLanguage() . '.php';

			if ($controller->isAjax())
			{
				require $content_tpl;
				$controller->afterRender();
			} else {
				require VIEWS_PATH . 'pjLayouts/' . $controller->getLayout() . '.php';
				$controller->afterRender();
			}
		}
	} else {
		echo 'class didn\'t exists';
		exit;
	}
}
?>
