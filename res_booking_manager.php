<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<div>
<?php   
$table_prefix = $_SESSION["session_loginname"];
$table_prefix = str_replace("-", "", $table_prefix);

$dns = sprintf("mysql:dbname=%s;host=%s", MYSQL_DB, MYSQL_HOST);
$dbh = new PDO($dns, MYSQL_USERNAME, MYSQL_PASSWORD);
$stm = $dbh->prepare('SELECT * FROM rb_users WHERE owner_id = ?');
$owner_id = intval($_SESSION['owner_id']);
$noError = $stm->execute([$owner_id]);

if ($noError === false) {
    die('System went down. Please contact the administrator.');
}

$user = $stm->fetch();
if(!empty($user)) {
    $plugins_url = "http://".$_SERVER['SERVER_NAME']."/resbooking/library/session.php?owner_id={$owner_id}&username=".$table_prefix;
} else {
    $plugins_url = "http://".$_SERVER['SERVER_NAME']."/resbooking/install.php?owner_id={$owner_id}&username=".$table_prefix;
}
global $userusername;
$userusername = $table_prefix;
?>  
    <iframe onLoad="calcHeight();" id="iFrame" width="100%" src="<?php echo $plugins_url; ?>"  height="1200" frameborder="0"></iframe>  
</div>

<?php include "includes/userfooter.php"; ?>
