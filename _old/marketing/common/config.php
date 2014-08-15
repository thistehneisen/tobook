<?php
require_once '../includes/configsettings.php';
define("DB_TYPE", "mysql");
define("DB_HOSTNAME", MYSQL_HOST);
define("DB_USERNAME", MYSQL_USERNAME);
define("DB_PASSWORD", MYSQL_PASSWORD);
define("DB_DATABASE", MYSQL_DB);

define("CREDITS_PRICE", 3);
define("CREDITS_EMAIL", 2);
define("CREDITS_SMS", 1);

define("INFOBIP_USERNAME", "varaa6");
define("INFOBIP_PASSWORD", "varaa12");

define("SENDGRID_USERNAME", "varaacom");
define("SENDGRID_PASSWORD", "mikael90");

define("EMAIL_FROM", "Klikkaja");
define("EMAIL_SENDER", "jenistar90@klikkaaja.com");

define("DEFAULT_TEMPLATE_EMAIL", "img/templateThumb/default.jpg");
define("PHONE_COUNTRY_CODE", "+358");
$emailCreators = array( "jenistar", "admin" );
?>