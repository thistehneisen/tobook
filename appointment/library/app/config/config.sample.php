<?php
if (!defined("PJ_HOST")) define("PJ_HOST", "[hostname]");
if (!defined("PJ_USER")) define("PJ_USER", "[username]");
if (!defined("PJ_PASS")) define("PJ_PASS", "[password]");
if (!defined("PJ_DB")) define("PJ_DB", "[database]");

if ( isset($_GET['as_pf']) ) {
	$as_pf = $_GET['as_pf'];
	setcookie("as_pf", $as_pf, time()+3600, "/", "");
} else  $as_pf = isset($_COOKIE['as_pf']) ? $_COOKIE['as_pf'] : null;

if (!defined("PJ_PREFIX")) define("PJ_PREFIX", "as_");

if (!defined("PJ_INSTALL_FOLDER")) define("PJ_INSTALL_FOLDER", "[install_folder]");
if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", "[install_path]");
if (!defined("PJ_INSTALL_URL")) define("PJ_INSTALL_URL", "[install_url]");
if (!defined("PJ_SALT")) define("PJ_SALT", "[salt]");
if (!defined("PJ_INSTALLATION")) define("PJ_INSTALLATION", "[pj_installation]");
?>
