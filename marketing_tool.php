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
		$loginId = $_SESSION["owner_id"];
		$plugins_url = "http://".$_SERVER['SERVER_NAME']."/marketing/index.php?username=$loginName"."&userid=$loginId";
		
		$sql = "select * from tbl_owner_premium where owner = $loginId and plan_group_code = 'mt'";
		$dataResult = mysql_query( $sql );
		$dataRow = mysql_fetch_array( $dataResult );
		if( $dataRow != null ){
			$accountCode = $dataRow["account_code"];
		}else{
			$accountCode = "";
		}
		
		$siteUrl = $_SERVER['SERVER_NAME'];
		$paymentUrl = "http://".$siteUrl."/gatewayPayment/paymentOne.php?userId=$loginId&accountCode=$accountCode";
	?>	
		<!-- TODO: Provide markup for your options page here. -->
	   	<iframe id="frame" src="<?php echo $plugins_url; ?>" width="100%" height="1000px" frameborder="0"></iframe>
</div>

	<div id="divWhiteBackground" onclick="onClosePayment( )" style="display:none; position:fixed; left: 0px; top: 0px; right: 0px; bottom: 0px; background:rgba( 250, 250, 250, 0.5 );"></div>
	<div id="divPayment" style="display:none; position: fixed; top:0px; left: 0px; right: 0px; bottom: 0px; z-index: 1000;">
		<iframe src="<?php echo $paymentUrl?>" id="iframePayment" style="width: 100%;height:100%;" frameborder="0" ></iframe>
		<!-- div onclick="onClosePayment( )" style="float:right; margin-right: 50px; margin-top: 10px; width: 120px; height: 35px; background:#3498db; line-height: 35px; text-align:center;color:#FFF; cursor: pointer;">Close</div  -->
	</div>
	<script>
		function onOpenPayment( ){
			$("#divWhiteBackground").fadeIn();
			$("#divPayment").fadeIn();
			$("body").css("overflow", "hidden" );
		}
		
		function onClosePayment( ){
			$("#divWhiteBackground").fadeOut( );
			$("#divPayment").fadeOut( );
			$("body").css("overflow", "inherit" );
		}
		function onAfterPayment( amount ){
			document.getElementById("frame").contentWindow.refreshCredits( amount );
			onClosePayment( );
		}
	</script>
<!--div class="comm_div" align="left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></div-->
<?php
include "includes/userfooter.php";
?>
<script>

</script>