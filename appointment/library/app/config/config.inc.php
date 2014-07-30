<?php
if (!defined("PJ_HOST")) define("PJ_HOST", "localhost");
if (!defined("PJ_USER")) define("PJ_USER", "root");
if (!defined("PJ_PASS")) define("PJ_PASS", "");
if (!defined("PJ_DB")) define("PJ_DB", "varaa");

if ( isset($_GET['as_pf']) ) {
	$as_pf = $_GET['as_pf'];
	setcookie("as_pf", $as_pf, time()+3600, "/", "");
} else  $as_pf = isset($_COOKIE['as_pf']) ? $_COOKIE['as_pf'] : null;

if (!defined("PJ_PREFIX")) define("PJ_PREFIX", "as_");

if (!defined("PJ_INSTALL_FOLDER")) define("PJ_INSTALL_FOLDER", "/appointment/library/");
if (!defined("PJ_INSTALL_PATH")) define("PJ_INSTALL_PATH", "/Users/hungnq/code/php/varaa/appointment/library/");
if (!defined("PJ_INSTALL_URL")) define("PJ_INSTALL_URL", "http://klikkaaja.loc/appointment/library/");
if (!defined("PJ_SALT")) define("PJ_SALT", "UPK2C3AI");
if (!defined("PJ_INSTALLATION")) define("PJ_INSTALLATION", "NjMyNDc4NzcxOTA5MTY4NzEzODMyNzk0MDkwNDc5ODI5OTIzMjg4NjY0ODY4MzQ3MTg1ODU4MDg1MDc2MjI3NTc5NTQ5NjczMjA1NjIzOTg2MTQzNDQxMTE3MjE5NTk4NTU5IDYxMTA1NjM5NDAwOTU2MzY1NzA0MTk0NzEwNTEyNjIwNTA4MDk2NDQ2NzExNzM4MDEwNzQ0ODI3OTA2ODY5NDA0OTU3NDEwNDEwOTk1NzEwNzYwNzc2NjAzNTQ4NjMxNzE3NyAxMjAyMDM2OTA2NDEwODcwMDYyOTUyMjUzNjEzNjc1NTE5MzU2MjY0MTA1OTczNzA1NDE0MzE0MzIzMjE2Nzg2NTM1OTI3Nzc0NjA4NjEyMDI5NzMzMDc2NDYyOTY5OTY0MDI0IDEwNDA5NzE4OTUyMDY3ODM3NTk2OTg3MDYxMzI2NTYyMzkyMjI5MzMxMjc2MjI0NzExOTM0NjExNTIxODEwNjE0OTI3NjExMTM4NTA5NTc0MzQwODg2NTk5OTUzNjE2NTM4NjQgNzcxMjA5NDg4NTUzNzgyNDE5ODUwMjU5MzYwMTgzNjUxMDI4NDk3NjU4NTQ3MjUyNDk1ODE5NDYzMzM2NzkyNTY1MDIxNTAwMzgxMTQ4OTkzMTI5Mjg0MTEzNDkxMTQ2NjM1");
?>
