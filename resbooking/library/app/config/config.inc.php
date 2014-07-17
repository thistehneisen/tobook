<?php
require_once(ROOT_PATH . 'app/controllers/components/pjUtil.component.php');
require_once("../../includes/configsettings.php");
$stop = false;
if (isset($_GET['controller']) && $_GET['controller'] == 'pjInstaller')
{
	$stop = true;
	if (isset($_GET['install']))
	{
		switch ($_GET['install'])
		{
			case 1:
				$stop = true;
				break;
			default:
				$stop = false;
				break;
		}
	}
}

if (!$stop)
{
	$dbPrefix = $userPrefix."_";
	if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{
		# LOCAL
		define("DEFAULT_HOST",   MYSQL_HOST);
		define("DEFAULT_USER",   MYSQL_USERNAME);
		define("DEFAULT_PASS",   MYSQL_PASSWORD);
		define("DEFAULT_DB",     MYSQL_DB);
		define("DEFAULT_PREFIX", $dbPrefix );
	} else {
		# REMOTE
		define("DEFAULT_HOST",   MYSQL_HOST);
		define("DEFAULT_USER",   MYSQL_USERNAME);
		define("DEFAULT_PASS",   MYSQL_PASSWORD);
		define("DEFAULT_DB",     MYSQL_DB);
		define("DEFAULT_PREFIX", $dbPrefix );
	}
	
	if (preg_match('/\[hostname\]/', DEFAULT_HOST))
	{
		pjUtil::redirect("index.php?controller=pjInstaller&action=step0&install=1");
	}

	$link = @mysql_connect(DEFAULT_HOST, DEFAULT_USER, DEFAULT_PASS);
	if (!$link) {
	    die('Could not connect: ' . mysql_error());
	}
	
	# mysql_query("SET SESSION time_zone = '-0:00';");
	mysql_query("SET NAMES 'utf8'", $link);
	
	$db_selected = mysql_select_db(DEFAULT_DB, $link);
	if (!$db_selected) {
	    die ('Can\'t select database: ' . mysql_error());
	}
	
	if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{
/* 		if (!defined("INSTALL_FOLDER")) define("INSTALL_FOLDER", "/resbooking/library/");
		if (!defined("INSTALL_PATH")) define("INSTALL_PATH", "D:\MyPhp\kika\resbooking/library/");
		if (!defined("INSTALL_URL")) define("INSTALL_URL", "http://www.kika.localhost/resbooking/library/"); */
		if (!defined("INSTALL_FOLDER")) define("INSTALL_FOLDER", "/resbooking/library/");
		if (!defined("INSTALL_PATH")) define("INSTALL_PATH", dirname(__FILE__)."/resbooking/library/");
		if (!defined("INSTALL_URL")) define("INSTALL_URL", "http://".$_SERVER['SERVER_NAME']."/resbooking/library/");		
	} else {
		if (!defined("INSTALL_FOLDER")) define("INSTALL_FOLDER", "/resbooking/library/");
		if (!defined("INSTALL_PATH")) define("INSTALL_PATH", dirname(__FILE__)."/resbooking/library/");
		if (!defined("INSTALL_URL")) define("INSTALL_URL", "http://".$_SERVER['SERVER_NAME']."/resbooking/library/");
	}
}

if (!defined("APP_PATH")) define("APP_PATH", ROOT_PATH . "app/");
if (!defined("CORE_PATH")) define("CORE_PATH", ROOT_PATH . "core/");
if (!defined("LIBS_PATH")) define("LIBS_PATH", "core/libs/");
if (!defined("FRAMEWORK_PATH")) define("FRAMEWORK_PATH", CORE_PATH . "framework/");
if (!defined("CONFIG_PATH")) define("CONFIG_PATH", APP_PATH . "config/");
if (!defined("CONTROLLERS_PATH")) define("CONTROLLERS_PATH", APP_PATH . "controllers/");
if (!defined("COMPONENTS_PATH")) define("COMPONENTS_PATH", APP_PATH . "controllers/components/");
if (!defined("MODELS_PATH")) define("MODELS_PATH", APP_PATH . "models/");
if (!defined("VIEWS_PATH")) define("VIEWS_PATH", APP_PATH . "views/");
if (!defined("WEB_PATH")) define("WEB_PATH", APP_PATH . "web/");
if (!defined("CSS_PATH")) define("CSS_PATH", "app/web/css/");
if (!defined("IMG_PATH")) define("IMG_PATH", "app/web/img/");
if (!defined("JS_PATH")) define("JS_PATH", "app/web/js/");
if (!defined("UPLOAD_PATH")) define("UPLOAD_PATH", "app/web/upload/");

if (!defined("SCRIPT_VERSION")) define("SCRIPT_VERSION", "1.0");
if (!defined("SCRIPT_ID")) define("SCRIPT_ID", "111");
if (!defined("SCRIPT_BUILD")) define("SCRIPT_BUILD", "1.0.5");
if (!defined("TEST_MODE")) define("TEST_MODE", false);
?>