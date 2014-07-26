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
	<iframe onLoad="calcHeight();" id="iFrame" width="100%" src="<?php echo $plugins_url; ?>"  height="1200" frameborder="0"></iframe>	
</div>

<?php
include "includes/userfooter.php";
?>