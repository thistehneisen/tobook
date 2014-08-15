<?php
require_once("../../includes/configsettings.php");
$prefix = $_GET['prefix'];
$owner_id = $_GET['owner_id'];

setcookie("as_db_version" . $prefix, "1", time()+3600, "/", "");


// fix empty session owner_id after signup
if(empty($_SESSION['owner_id']) && !empty($_GET['owner_id'])){
    $_SESSION['owner_id'] = $owner_id;
}

$db = array();
$db['n'] 		= MYSQL_DB;
$db['u'] 		= MYSQL_USERNAME;
$db['h'] 		= MYSQL_HOST;
$db['pf'] 		= $prefix;

$db  = http_build_query($db);
$dbp = MYSQL_PASSWORD;

$con=mysql_connect(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASSWORD) or die (mysql_error());
$db=mysql_select_db(MYSQL_DB,$con) or die(mysql_error());

$sql = sprintf("SELECT COUNT(*) FROM as_users WHERE owner_id = %d", $owner_id);
$result = mysql_query($sql, $con);
if((int) mysql_result($result, 0) > 0){
	$firstYN = "N";
}else{
	$firstYN = "Y";
}

setcookie("appointment_scheduler", $db, time()+36000, "/", "");
setcookie("appointment_scheduler_p", $dbp, time()+36000, "/", "");

header("location: index.php?as_pf=$prefix&owner_id=$owner_id&firstYN=$firstYN");

?>
