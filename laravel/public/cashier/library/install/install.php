<?php

/************************************
*   @author         Mian Saleem     *
*   @package        SMA2            *
*   @subpackage     install         *
************************************/

$indexFile = "../index.php";
$configFolder = "../sma/config";
$configFile = "../sma/config/config.php";
$dbFile = "../sma/config/database.php";

require_once("../../../includes/configsettings.php");

global $prefix; 
    
$db_url = isset($_COOKIE['cashier_manager']) ? $_COOKIE['cashier_manager'] : null;
$db = array();

if (isset($db_url)) {
    $db_url = urldecode($db_url);

    foreach ( explode('&', $db_url) as $value ) {
        $_db = explode('=', $value);
        $db[$_db[0]] = $_db[1];
    }
}

$dbpw = isset($_COOKIE['cashier_manager_p']) ? $_COOKIE['cashier_manager_p'] : '';


$localpath = str_replace("\\", "/", dirname(getenv("SCRIPT_NAME")));

$localpath = str_replace("\\", "/", $localpath);
$localpath = preg_replace('/^\//', '', $localpath, 1) . '/';
$localpath = !in_array($localpath, array('/', '\\')) ? $localpath : NULL;

$install_url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $localpath;
    
    
switch($_GET['step']){
    default: ?>
        <h3>Installation</h3>
<?php 
    $code = 'e499efe2-7f15-4be8-9f9a-6694026b5c39'; //$_POST["code"];
    $username = 'varaacom1'; //$_POST["username"];
    $mail = 'varaacom1@gmail.com';
    
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'http://tecdiary.com/support/api/register/');
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    $referer = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -15);
    $path = substr(realpath(dirname(__FILE__)), 0, -8);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
    'username' => $_POST["username"],
    'email' => $_POST["email"],
    'code' => $_POST["code"] ,
    'id' => '5403161',
    'ip' => $_SERVER['REMOTE_ADDR'],
    'referer' => $referer,
    'path' => $path
    ));
        
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
        
    $object = json_decode($buffer);
    
    
    $dbhost = MYSQL_HOST;
    $dbusername = MYSQL_USERNAME;
    $dbpassword = MYSQL_PASSWORD;
    $dbname = MYSQL_DB;
    $dbprefix = isset($prefix) ? $prefix . 'sma_' : 'sma_';

    $link = @mysql_connect($dbhost, $dbusername, $dbpassword);

    if (!$link) {
        echo "<div class='alert alert-error'><i class='icon-remove'></i> Could not connect to MYSQL!</div>";
    } else {
        echo '<div class="alert alert-success"><i class="icon-ok"></i> Connection to MYSQL successful!</div>';
    }

    define("BASEPATH", "install/");
    include("../sma/config/database.php");
    $file_sql = 'config/database.sql';
    $database_str = '';
    
    if (is_file($file_sql)) {
        ob_start();
        readfile($file_sql);
        $database_str = ob_get_contents();
        ob_end_clean();
        if ($database_str !== false) {
            $database_str = preg_replace(
                array(
                    '/INSERT\s+INTO\s+`/',
                    '/DROP\s+TABLE\s+`/',
                    '/DROP\s+TABLE\s+IF\s+EXISTS\s+`/',
                    '/CREATE\s+TABLE\s+`/',
                    '/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/'
                ),
                array(
                    'INSERT INTO `'.$db['default']['dbprefix'],
                    'DROP TABLE `'.$db['default']['dbprefix'],
                    'DROP TABLE IF EXISTS `'.$db['default']['dbprefix'],
                    'CREATE TABLE `'.$db['default']['dbprefix'],
                    'CREATE TABLE IF NOT EXISTS `'.$db['default']['dbprefix']
                ),
                $database_str
            );
        }
    }
    $dbdata = array(
        'hostname' => $db['default']['hostname'],
        'username' => $db['default']['username'],
        'password' => $db['default']['password'],
        'database' => $db['default']['database'],
        'dbtables' => $database_str
    );
    require_once('includes/database_class.php');
    $database = new Database();
    if ($database->create_tables($dbdata) == false) {
        $finished = FALSE;
    } else {
        $finished = TRUE;
            if(!@unlink('../SMA2')){
            // echo "<div class='alert alert-warning'><i class='icon-warning'></i> Please remove the SMA2 file from the main folder in order to lock the ipdate tool.</div>";
            }
    }
    if($finished) {?>
        <h3><i class='icon-ok'></i> Installation completed!</h3>
        <div class="alert alert-info">
            <i class='icon-info-sign'></i> You can create your account on the login page:<br /><br />
        </div>
        <a href="/cashier/library/index.php?module=auth&view=login&prefix=<?php echo $prefix; ?>" class="btn btn-success pull-right" style="padding: 6px 15px;">Login Page</a>
        <div style="clear:both;"></div>
    <?php
        }
    }
?>

 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">How to find your purchase code</h3>
  </div>
  <div class="modal-body">
    <img src="img/purchaseCode.png">
  </div>
</div>