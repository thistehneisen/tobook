<?php

@ini_set('zend.ze1_compatibility_mode', 0);
set_magic_quotes_runtime(0);
if (get_magic_quotes_gpc()) {
    function undoMagicQuotes($array, $topLevel=true) {
        $newArray = array();
        foreach($array as $key => $value) {
            if (!$topLevel) {
                $key = stripslashes($key);
            }
            if (is_array($value)) {
                $newArray[$key] = undoMagicQuotes($value, false);
            }
            else {
                $newArray[$key] = stripslashes($value);
            }
        }
        return $newArray;
    }
    $_GET = undoMagicQuotes($_GET);
    $_POST = undoMagicQuotes($_POST);
    $_COOKIE = undoMagicQuotes($_COOKIE);
    $_REQUEST = undoMagicQuotes($_REQUEST);
}
/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */
/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__FILE__))));
}
/**
 * The actual directory name for the "app".
 *
 */
if (!defined('APP_DIR')) {
    define('APP_DIR', basename(dirname(dirname(__FILE__))));
}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
    define('CAKE_CORE_INCLUDE_PATH', ROOT);
}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', dirname(__FILE__) . DS);
}
if (!defined('CORE_PATH')) {
    if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
        define('APP_PATH', null);
        define('CORE_PATH', null);
    } else {
        define('APP_PATH', ROOT . DS . APP_DIR . DS);
        define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
    }
}
if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
    trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}
$start=array();
$start["language"]=Configure::read('Config.language')==""?"eng":Configure::read('Config.language');
$start["custom1_show"]=1;
$start["custom2_show"]=1;
$start["custom3_show"]=1;
$start["custom4_show"]=1;
$start["custom4_show"]=1;
$start["custom1_label"]="Custom Field 1";
$start["custom2_label"]="Custom Field 2";
$start["custom3_label"]="Custom Field 3";
$start["custom4_label"]="Custom Field 4";
$start["api_show"]=1;
$start["cron_show"]=1;
$start["ctitle"]="My Rss Feed";
$start["cdescription"]="Edit Description in Application Settings";
$start["clink"]="http://google.com";
$start["author"]="myselfe";
$start["rssitems"]="10";

Configure::write("Settings", $start);
if (file_exists(CONFIGS . 'settings.yml'))
    Configure::write("Settings", array_merge ($start, json_decode(file_get_contents(CONFIGS . 'settings.yml'), true)));

 
if(count($argv) == 3 && php_sapi_name() === "cli") 
 {
 	# Set request URI
 	$_SERVER['REQUEST_URI'] = $argv[2];
 	# Set user-agent, so we can do custom processing
 	$_SERVER['HTTP_USER_AGENT'] = 'cron';
 	 define('FULL_BASE_URL',$argv[1]);
 	$Dispatcher= new Dispatcher();
 
$_GET['url']=$argv[2];
 	$Dispatcher->dispatch( );
 } 
