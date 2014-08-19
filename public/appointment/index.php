<?php
require_once realpath(__DIR__.'/../../Bridge.php');
$varaaDb = Bridge::dbConfig();

//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
$isEmbeded = (isset($_GET['controller'])
	&& ($_GET['controller'] === 'pjAdminOptions'
		|| $_GET['controller'] === 'pjFrontEnd'
		|| $_GET['controller'] === 'pjFrontPublic'));

if (!Bridge::hasOwnerId() && !$isEmbeded) {
	@session_destroy();
	echo <<< JS
<script>
window.parent.location = '/auth/login';
</script>
JS;
	exit;
}

global $owner_id;
@session_start();

if( !isset($_COOKIE['as_admin']) ){
	setcookie( "as_admin", "admin",	time() + (10 * 365 * 24 * 60 * 60) );
}
if (!headers_sent())
{
	// session_name('AppointmentScheduler');
}

header("Content-type: text/html; charset=utf-8");
if (!defined("ROOT_PATH"))
{
	define("ROOT_PATH", dirname(__FILE__) . '/');
}

if ( isset($_GET['username']) && !empty($_GET['username']) ) {
	$username = $_GET['username'];
	setcookie("username", $username, time()+3600, "/", "");
} else  $username = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;

define('PREFIX', $username);
require_once ROOT_PATH . 'app/controllers/components/pjUtil.component.php';

global $firstYN;
if( isset($_GET['firstYN']))
	$firstYN = $_GET['firstYN'];
else
	$firstYN = "N";

require ROOT_PATH . 'app/config/options.inc.php';

# Language
if (file_exists(ROOT_PATH . 'app/locale/fi.php')) {
	require ROOT_PATH . 'app/locale/fi.php';
}

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
