<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";

$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_APP_SCHEDULE_MANAGER =>'appointment_schedule_manager.php');
echo getBreadCrumb($linkArray);
?>

<h2><?php echo FOOTER_APP_SCHEDULE_MANAGER; ?></h2>

<div class="cpanel_container">
	
	<?php 	
	
	$table_prefix = $_SESSION["session_loginname"]."_hey"."_";
	$table_prefix = str_replace("-", "", $table_prefix);
	$plugins_url = "http://".$_SERVER['SERVER_NAME']."/appointment/library/installation.php?prefix=".$table_prefix;
	
	global $userusername;
	$userusername = $_SESSION["session_loginname"];
	?>	
	<div style="width:100%;">
		<!-- TODO: Provide markup for your options page here. -->
	   	<iframe id="frame" src="<?php echo $plugins_url; ?>" width="100%" height="1000px" frameborder="0"></iframe>
	</div>	
</div>

<?php
include "includes/userfooter.php";
?>