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
	$sql = "SHOW TABLES like '".$table_prefix."_sma_billers'";
	if(mysql_num_rows(mysql_query( $sql ))==1)
		$install = '&amp;module=home';
	else 
		$install = '&amp;install=1';
	
	$plugins_url = "http://".$_SERVER['SERVER_NAME']."/cashier".'/library/index.php?prefix='. $table_prefix."_" . $install;
	?>	
	<iframe onLoad="calcHeight();" id="iFrame" width="100%" src="<?php echo $plugins_url; ?>"  height="1200" frameborder="0"></iframe>
</div>
<?php
include "includes/userfooter.php";
?>