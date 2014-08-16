<?php
if (!defined("PJ_HOST")) define("PJ_HOST", "localhost");
if (!defined("PJ_USER")) define("PJ_USER", "root");
if (!defined("PJ_PASS")) define("PJ_PASS", "");
if (!defined("PJ_DB")) define("PJ_DB", "varaa_stag");

if ( isset($_GET['as_pf']) ) {
	$as_pf = $_GET['as_pf'];
	setcookie("as_pf", $as_pf, time()+3600, "/", "");
} else {
    $as_pf = isset($_COOKIE['as_pf']) ? $_COOKIE['as_pf'] : null;  
}

if (!defined("PJ_PREFIX")) define("PJ_PREFIX", "as_");
if (!defined("PJ_INSTALL_FOLDER")) define("PJ_INSTALL_FOLDER", "/appointment/");
//if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", "/var/www/cmd/../public/appointment/");
if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", dirname(__FILE__)."/../../../appointment/");
if (!defined("PJ_INSTALL_URL")) define("PJ_INSTALL_URL", "http://varaa.dev/appointment/");
if (!defined("PJ_SALT")) define("PJ_SALT", "14F86S4R");
if (!defined("PJ_INSTALLATION")) define("PJ_INSTALLATION", "MTI0MzcwNDMyMzgzMDk2NTk5OTIwNzM5NjA5NjU4NjQ5NDI4MTAzNTI4ODMyNjcxNzkxMTE1Mzc4NzQ1OTk0MTY4MTIwMDIzODQ5NDY1MDg3MDY4NTA2ODE2MDY5MTI3NDgyNSA5ODI0OTQ4MDYwODU5OTA3MDEzNjIwMzk5NjQzMjg4NjA5OTA1OTQwNjE2MjMzNjYzNTkwNzk4NTU3NzI0NjgwMTU5NTg3OTI5MjQwNzk5MDUwNTE0NzEzMjg0ODEyMzU5NjggNjk4NjY3MzkzODQzOTAyMDg4MjU5MzgzMjU3OTAwNjY1Nzk4MDQyOTYyNDU1NTAzNzk0NjM4NDYwMjAzNDgzOTIxMjY2MjY2MTg3MDI3MTAwNDA0NjgxNjgwNTg5NzQ4MjQw");
