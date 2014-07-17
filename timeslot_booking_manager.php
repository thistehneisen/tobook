<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";

$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_TS_BOOKING_MANAGER =>'timeslot_booking_manager.php');
echo getBreadCrumb($linkArray);
?>

<h2><?php echo FOOTER_TS_BOOKING_MANAGER; ?></h2>

<div class="cpanel_container">
	
	<?php 	
	
	$table_prefix = $_SESSION["session_loginname"];
	$table_prefix = str_replace("-", "", $table_prefix);
	$sql = "SHOW TABLES like '".$table_prefix."_ts_booking_bookings'";
	if(mysql_num_rows(mysql_query( $sql ))==1){
		$plugins_url = "http://".$_SERVER['SERVER_NAME']."/timeslot/library/session.php?username=".$table_prefix;
	}
	else{
		$plugins_url = "http://".$_SERVER['SERVER_NAME']."/timeslot/installation.php?username=".$table_prefix;
	}	

	global $userusername;
	$userusername = $table_prefix;
	?>	
	<div style="width:1200px; margin-left: -150px;">
		<!-- TODO: Provide markup for your options page here. -->
	   	<iframe id="frame" src="<?php echo $plugins_url; ?>" width="100%" height="1000px" frameborder="0"></iframe>
	</div>	
</div>

<?php
include "includes/userfooter.php";
?>