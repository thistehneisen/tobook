<?php
error_reporting(0);
require("install_config.php"); 
require("configsettings.php"); 
if(!INSTALLED) {
    header("Location:./install/index.php");
    exit;
}

$con=mysql_connect(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASSWORD) or die (mysql_error());
$db=mysql_select_db(MYSQL_DB,$con) or die(mysql_error());

include 'globalfunctions.php'; 

if(file_exists('language/english_lng.php')) {
    include 'language/english_lng.php';
}
else {
    include '../language/english_lng.php';
}
if(file_exists('language/english_lng_admin.php')) {
	include 'language/english_lng_admin.php';
}
else {
	include '../language/english_lng_admin.php';
}
if(file_exists('language/english_lng_user.php')) {
	include 'language/english_lng_user.php';
}
else {
	include '../language/english_lng_user.php';
}

$siteLanguageOption = getSettingsValue("site_language_option");

?>