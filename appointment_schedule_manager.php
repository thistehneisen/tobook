<?php
$curTab = 'dashboard';

//include Files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<!-- div style="position:absolute;left:0px;top:110px;right:0px;bottom:35px;" -->
<div>
	<?php
	$table_prefix = $_SESSION["session_loginname"]."_hey"."_";
	$table_prefix = str_replace("-", "", $table_prefix);
	$plugins_url = "http://".$_SERVER['SERVER_NAME']."/appointment/library/installation.php?owner_id={$_SESSION['owner_id']}&prefix=".$table_prefix;
	
	global $userusername;
	$userusername = $_SESSION["session_loginname"];
	?>	
	<iframe border="0" cellspacing="0" cellpadding="0" onLoad="calcHeight();" id="iFrame" width="100%" src="<?php echo $plugins_url; ?>"  height="1200" frameborder="0"></iframe>	
</div>
<script>
	/*
	$.receiveMessage(
        function(e) {
            alert( "e.data : " + e.data ); 
        	document.getElementById('iFrame').height = e.data;
        }  
    );
    */	
</script>
<?php
include "includes/userfooter.php";
?>
