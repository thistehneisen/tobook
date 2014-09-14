<?php
require_once realpath(__DIR__.'/../../Bridge.php');
$varaaDb = Bridge::dbConfig();

//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
if (!Bridge::hasOwnerId()) {
	@session_destroy();
	echo <<< JS
<script>
window.parent.location = '/business/auth/login';
</script>
JS;
	exit;
}

/**
 * @package tsbc
 */
if( !isset($_COOKIE['tsbc_admin']) ){
	setcookie( "tsbc_admin", "admin",	time() + (10 * 365 * 24 * 60 * 60) );
}
if (!headers_sent())
{
	@session_start();
}
header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

if (!isset($_SESSION['session_loginname'])) {
    $scheme = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
    header("Location: {$scheme}://{$_SERVER['HTTP_HOST']}");
}

$userPrefix = $_SESSION['session_loginname'];
require_once ROOT_PATH . 'app/config/config.inc.php';
require ROOT_PATH . 'oneapi.php';
if (!isset($_GET['controller']) || empty($_GET['controller']))
{
	header("HTTP/1.1 301 Moved Permanently");
	Util::redirect(BASE_PATH . basename($_SERVER['PHP_SELF'])."?controller=Admin&action=index");
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
				require VIEWS_PATH . 'Layouts/' . $controller->getLayout() . '.php';
				$controller->afterRender();
			}
		}
	} else {
		echo 'class didn\'t exists';
		exit;
	}
}
?>
