<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<div>
	<?php
    error_reporting(E_ALL);
    $owner_id = (int) $_SESSION['owner_id'];
	$sql = "SELECT COUNT(*) FROM ts_calendars WHERE owner_id = ". $owner_id;
	if(mysql_result(mysql_query($sql), 0, 0) == 1){
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
