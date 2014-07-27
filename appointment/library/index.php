<?php
global $owner_id;
$owner_id = $_GET['owner_id'];
@session_start();
if( !isset($_COOKIE['as_admin']) ){
	setcookie( "as_admin", "admin",	time() + (10 * 365 * 24 * 60 * 60) );
}
if (!headers_sent())
{
	session_name('AppointmentScheduler');
}
if(!isset($_SESSION['owner_id'])){
	$_SESSION['owner_id'] = $owner_id;
}
if (in_array($_SERVER['SERVER_ADDR'], array('127.0.0.1', '192.185.5.15', '::1')))
{
	ini_set("display_errors", "On");
	error_reporting(E_ALL|E_STRICT);
} else {
	error_reporting(0);
}
header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

if ( isset($_GET['as_pf']) && !empty($_GET['as_pf']) ) {
	$as_pf = $_GET['as_pf'];
	setcookie("as_pf", $as_pf, time()+3600, "/", "");
} else  $as_pf = isset($_COOKIE['as_pf']) ? $_COOKIE['as_pf'] : null;

define('PREFIX', $as_pf);
require_once ROOT_PATH . 'app/controllers/components/pjUtil.component.php';

global $firstYN;
if( isset($_GET['firstYN']))
	$firstYN = $_GET['firstYN'];
else
	$firstYN = "N";

require ROOT_PATH . 'app/config/options.inc.php';

# Language
if (file_exists(ROOT_PATH . 'app/locale/en.php'))
	require ROOT_PATH . 'app/locale/en.php';

//require ROOT_PATH . 'oneapi.php';
if (!isset($_GET['controller']) || empty($_GET['controller']))
{
	header("HTTP/1.1 301 Moved Permanently");
	pjUtil::redirect(PJ_INSTALL_URL . basename($_SERVER['PHP_SELF'])."?controller=pjAdmin&action=pjActionIndex&owner_id=".$owner_id);
}
if (isset($_GET['controller']))
{
	require_once PJ_FRAMEWORK_PATH . 'pjObserver.class.php';
	$pjObserver = pjObserver::factory();
	$pjObserver->init();
}
?>
