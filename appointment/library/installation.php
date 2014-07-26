<?php
require_once("../../includes/configsettings.php");
$prefix = $_GET['prefix'];
$owner_id = $_GET['owner_id'];

setcookie("as_db_version" . $prefix, "1", time()+3600, "/", "");

$db = array();
$db['n'] 		= MYSQL_DB;
$db['u'] 		= MYSQL_USERNAME;
$db['h'] 		= MYSQL_HOST;
$db['pf'] 		= $prefix;

$db = http_build_query($db);
$dbp 		= MYSQL_PASSWORD;

$con=mysql_connect(MYSQL_HOST,MYSQL_USERNAME,MYSQL_PASSWORD) or die (mysql_error());
$db=mysql_select_db(MYSQL_DB,$con) or die(mysql_error());

$sql = "SHOW TABLES like '".$prefix."appscheduler_bookings'";
if(mysql_num_rows(mysql_query( $sql ))==1){
	$firstYN = "N";
}else{
	$firstYN = "Y";
}

setcookie("appointment_scheduler", $db, time()+36000, "/", "");
setcookie("appointment_scheduler_p", $dbp, time()+36000, "/", "");

header("location: index.php?as_pf=$prefix&owner_id=$owner_id&firstYN=$firstYN");

?>
