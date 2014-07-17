<?php
require_once("../../includes/configsettings.php");
if (!defined("PJ_HOST")) define("PJ_HOST", MYSQL_HOST);
if (!defined("PJ_USER")) define("PJ_USER", MYSQL_USERNAME);
if (!defined("PJ_PASS")) define("PJ_PASS", MYSQL_PASSWORD);
if (!defined("PJ_DB")) define("PJ_DB", MYSQL_DB);

if ( isset($_GET['as_pf']) ) {
	$as_pf = $_GET['as_pf'];
	setcookie("as_pf", $as_pf, time()+3600, "/", "");
} else  $as_pf = isset($_COOKIE['as_pf']) ? $_COOKIE['as_pf'] : null;

if ( isset($as_pf) ) {
	if (!defined("PJ_PREFIX")) define("PJ_PREFIX", $as_pf);
} else
	if (!defined("PJ_PREFIX")) define("PJ_PREFIX", "jenistar_");

if (!defined("PJ_INSTALL_FOLDER")) define("PJ_INSTALL_FOLDER", "/appointment/library/");
if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", dirname(__FILE__)."/../../../../appointment/library/");
// if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", "D:/MyPhp/kika/appointment/library/");
if (!defined("PJ_INSTALL_URL")) define("PJ_INSTALL_URL", "http://".$_SERVER['SERVER_NAME']."/appointment/library/");
if (!defined("PJ_SALT")) define("PJ_SALT", "13J9P33E");
if (!defined("PJ_INSTALLATION")) define("PJ_INSTALLATION", "MTIyMjI1MjEwMjQ2NDM0NzgxMDU1MDg4NjgzNTgxNTA4NzM0MzQ1ODYzNDg0OTgyMDMwNTk0MDgxMzkwNTAxODk3NzYwMjUxNjIxNTQxMTM5MTk0ODU5MjczMTM3NTM2ODcwMSAxMTc2MjA4NDAwODQ2MTM0ODMzODQwMjgyNDg2MDIzMDMzMTQ0NDkxNDU0OTM4MDM3NDQ4MTU1OTMxMzg4NTgzMDA5NTk3MjQ0ODY0MTQ4ODY4MzExNjE0ODg5MzA5MjU1MzY2IDY3OTk3NzA0MzAxODEzMDQwNjk4NzU4MzMxMTIyNjAyMTk0NDYwMDYzMzc5MDY1NDczOTY1MTk5NzAwMTU0NzA4NzA4MjAzNzQ5ODgxNTczNDQ3MjAxMTA4NjUxNDE5NjI4OSA1NTU3Nzg1MDI2MjM4MjUzMDQwODc1OTEyMDY1Nzg1NjY2MzA5MjE4NzU2MTgxNjgyNDEwNDMxMjkwMjEyNTY1MDQ3MjU4NjE2NjUxMTg4MjY1MTMwNzY1OTg1NjM1NzgwNCA2ODYwMDIyMDM4MDA5OTk0ODE5OTMzMDAwOTg0NTg4ODg4MTIxNzA5ODU1NzQ1ODk3NTg4MDk3NDUyNTA3MzgwNjM4NDI1OTA4NzI4OTYyOTQyNTYxODg1NTU1MDgyMTE5NzggMTg1NzMzNDQ2NjIxNTc4NzM0OTQ2MTA2OTMzNjY4OTY2MDQ5MDk1MDI2MDA2OTI5MzgyODUxNzMxMjI3NDU0MzI2MDUzNDI5Mjc2NDg4ODI1MDA5ODA1ODg2NDY0NDYxNTE4");
?>