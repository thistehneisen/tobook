<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<div>
	
	<?php 	
		$loginName = $_SESSION["session_loginname"];
		$loginId = $_SESSION["session_userid"];
		$plugins_url = "http://".$_SERVER['SERVER_NAME']."/marketing/consumerList.php?username=$loginName"."&userid=$loginId";
	?>	
	   	<iframe id="frame" src="<?php echo $plugins_url; ?>" width="100%" height="1000px" frameborder="0"></iframe>
</div>
<?php
include "includes/userfooter.php";
?>
<script>

</script>