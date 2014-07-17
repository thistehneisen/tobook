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
                    FOOTER_CASHIER_MANAGER =>'cashiermanager.php');
echo getBreadCrumb($linkArray);
?>

<h2><?php echo FOOTER_CASHIER_MANAGER; ?></h2>

<div class="cpanel_container">

	<?php 
	$table_prefix = $_SESSION["session_loginname"];
	$table_prefix = str_replace("-", "", $table_prefix);
	$sql = "SHOW TABLES like '".$table_prefix."_sma_billers'";
	if(mysql_num_rows(mysql_query( $sql ))==1)
		$install = '&amp;module=home';
	else 
		$install = '&amp;install=1';
	
	$plugins_url = "http://".$_SERVER['SERVER_NAME']."/cashier";
	?>	
	<div style="width:1300px; margin-left: -200px;">
		<!-- TODO: Provide markup for your options page here. -->
		<iframe id="frame" src="<?php echo $plugins_url . '/library/index.php?prefix='. $table_prefix."_" . $install; ?>" width="100%" height="1300px" frameborder="0">
	   	</iframe>
	</div>	
</div>

<!--div class="comm_div" align="left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></div-->
<?php
include "includes/userfooter.php";
?>