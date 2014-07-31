<?php

error_reporting(0);

define('ENVIRONMENT', 'GLOBAL');

$file = realpath(__DIR__ . '/../config.php');
if (!$file) {
    die('Configuration file does not exist.');
}

$config = require_once $file;

define('MYSQL_HOST',        $config['db']['host']);
define('MYSQL_USERNAME',    $config['db']['user']);
define('MYSQL_PASSWORD',    $config['db']['password']);
define('MYSQL_DB',          $config['db']['name']);
define('MYSQL_TABLE_PREFIX','tbl_');
define('BASE_URL_PART','');

define('SMTP_HOST', 'smtp.mandrillapp.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'hieu@varaa.com');
define('SMTP_PASSWORD', isset($config['smtp']['password']) ? $config['smtp']['password'] : 'CTzo04IR5HYpn9LjCGV-7A');


$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
$currentDomain =  $_SERVER['SERVER_NAME'];

if(strlen($currentDomain) - 1 <> '/'){
    $currentDomain .= '/';
}

$baseUrl = $protocol.$currentDomain.BASE_URL_PART;

define('BASE_URL',$baseUrl);


$_SESSION["session_template_dir"] = 'templates';

define('EDITOR_USER_IMAGES', 'uploads/siteimages/' );
define('USER_SITE_UPLOAD_PATH','workarea/sites/');
define('EDITOR_IMAGES','editor/images/');
define('EDITOR_APP_SOCIAL_SHARE',2);
define('TEMPLATE_PREFIX','T');

// Manage Currency
$currencyArray = array( 
    "USD" => array('title'=>'US Dollar','code' => 'USD','symbol' => '$'),
    "EUR" => array('title'=>'Euro','code' => 'EUR','symbol' => '&euro;'),
    "GBP" => array('title'=>'Pound','code' => 'GBP','symbol' => '&pound;'),
    "CAD" => array('title'=>'Canadian Dollar','code' => 'CAD','symbol' => '$'),
    "INR" => array('title'=>'Indian Rupee','code' => 'INR','symbol' => '&#x20a8;')
);

$fontList = array(	
    'Arial' 				=> 'Arial, Helvetica, sans-serif',
    'Arial Black' 			=> '"Arial Black", Gadget, sans-serif',
    'Comic Sans MS' 		=> 'Comic Sans MS, Comic Sans MS, cursive',
    'Courier' 				=> '"Courier New", Courier, monospace',
    'Courier New' 			=> '"Courier New", Courier, monospace',
    'Georgia' 				=> 'Georgia, "Times New Roman", Times, serif',
    'Geneva' 				=> 'Geneva, Arial, Helvetica, sans-serif',
    'Impact' 				=> 'Impact, Impact, Charcoal, sans-serif',
    'Lucida Console' 		=> 'Lucida Console, Monaco, monospace',
    'Lucida Sans Unicode' 	=> 'Lucida Sans Unicode, Lucida Grande, sans-serif',
    'MS Sans Serif' 		=> 'MS Sans Serif, Geneva, sans-serif',
    'MS Serif' 				=> 'MS Serif, New York, serif',
    'Palatino Linotype' 	=> 'Palatino Linotype, Book Antiqua, Palatino, serif',
    'Tahoma' 				=> 'Tahoma, Geneva, sans-serif',
    'Times New Roman' 		=> '"Times New Roman", Times, serif',
    'Trebuchet MS' 			=> 'Trebuchet MS, Trebuchet MS, sans-serif',
    'Verdana' 				=> 'Verdana, Arial, Helvetica, sans-serif'
);
