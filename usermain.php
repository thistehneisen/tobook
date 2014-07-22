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
session_start();
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader_home.php";

if($_SESSION['session_lookupsitename'] == '')
	$_SESSION['session_lookupsitename'] = getSettingsValue('site_name');
?>

<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>

<style>
.manage_box_hdr{ background-color : #ec7923; height: 35px;}
.manage_box_hdr h3{ color: #FFF; font-family: 'Comfortaa'; font-size:16px;}
.manage_box{ width: 230px; margin-right: 20px; height: 170px; }
.manage_box a{ width: 230px; }
.manage_box img{ width: 100px; margin}
.mainTopNavigation{ padding-top:40px;font-weight:500;font-family:'Comfortaa';padding-bottom:10px;text-align:center; }
.mainTopNavigation a{ color: #000; margin-left:20px; margin-right:20px; font-size:18px;}
.mainTopNavigation a:hover, .navSelected{ color: #ec7923 !important; }
table#tblSiteList tr th{ color: #ec7923; } 
.topNavigationMenu{ position:absolute;top:30px;right: 30px;font-family: Arial;font-size:12px; z-index:1000;}
.topNavigationMenu a{ color: #000; text-decoration:none; font-weight:bold;}
.topNavigationMenu a:hover{ color: #FFF; text-decoration:none;}
.topNavigationMenu a.selected{ color: #FFF; font-weight: bold;}
</style>
<body>
		<?php if( isset($_SESSION["session_loginname"]) ){?>
		<div class="topNavigationMenu">
			<a href="websiteList.php">Etusivu</a>&nbsp;|&nbsp;
			<a href="usermain.php?succ=msg" class="selected">Hallintapaneeli</a>&nbsp;|&nbsp;
			<a href="profilemanager.php">Omat tiedot</a>&nbsp;|&nbsp;
			<a href="#" onclick="javascript:openWindow('http://www.klikkaaja.com/userhelp/index.html');">Apua</a>&nbsp;|&nbsp;
			<a href="logout.php">Kirjaudu Ulos</a>
		</div>		
		<?php }else{?>
		<div class="topNavigationMenu">
			<a href="websiteList.php">ETUSIVU</a>&nbsp;|&nbsp;
			<a href="signup.php">REKISTERÖIDY</a>&nbsp;|&nbsp;
			<a href="login.php">KIRJAUDU</a>
		</div>
		<?php } ?>
		
<div class="guideTopArea" style="position:relative;">
	<div class="guideTopVaraa">
		<span>varaa</span><span class="fontBlack">.com</span>
	</div>
	<div class="guideTopTitle">
		<span>Hallintapaneeli</span>
	</div>
	<?php if(isset($_SESSION["session_loginname"]) || $_SESSION["session_loginname"]!="") { ?>
	<div id="mainHeaderWelcome">
		<?php echo DASHBOARD_WELCOME; ?> <span ><?php echo ucwords($_SESSION['session_loginname']);?></span>
	</div>
	<?php } ?>
</div>
<div class="greyDivider"></div>

<input type="hidden" id="userId" value="<?php echo $_SESSION["session_userid"]?>"/>

<?php if($_GET['action'] == 'newuser'){	?>
<div class="nwusrwelcome">
	<div class="nwusrwelcometitle"><?php echo WELCOME ;?> <?php echo $_SESSION['session_loginname'];?>,</div>
	<div class="nwusrwelcomemessage"><?php echo WELCOME_TO ;?> <?php echo $_SESSION['session_lookupsitename']; ?>.
	<?php echo WELCOME_NOTE ;?>
	 </div>
	
	</div>
<?php } ?>

<div class="cpanel_container">
	<div class="managing_options" style="width:1000px;margin:0px auto;">
		
		<div style="padding-top:50px;color:#ec7923;font-size:30px;font-weight:500;font-family:'Comfortaa';padding-bottom:10px;"><?php echo TOP_LINKS_DASHBOARD;?></div>	
		<div class="manage_box">
			<div class="manage_box_hdr">
				<a href="sitemanager.php"><h3><?php echo SITE_MANAGER ;?></h3></a>
			</div>
			<a href="sitemanager.php"><img border="0" src="images/sitemanager.jpg"></a>
			<div class="clear"></div>
		</div>
		
		<div class="manage_box">
			<div class="manage_box_hdr">
				<a href="gallerymanager.php"><h3><?php echo GALLERY_MANAGER ;?>	</h3></a>
			</div>
			<a href="gallerymanager.php"><img border="0" align="absmiddle" src="images/gallerymanager.jpg"></a>
		</div>
		<div class="manage_box">
			<div class="manage_box_hdr">
				<a href="profilemanager.php"><h3><?php echo PROFILE_MANAGER ;?></h3></a>
			</div>
			<a href="profilemanager.php"><img border="0" src="images/profilemanager.jpg"></a>
		</div>
		
		<div class="manage_box no_margin">
			<div class="manage_box_hdr">
				<a href="promotionmanager.php"><h3><?php echo PROMOTION_MANAGER ;?></h3></a>
			</div>
			<a href="promotionmanager.php"><img border="0" src="images/promotionmanager.jpg"></a>
		</div>
		
		<div class="manage_box" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a onclick="paymentStatus('cm');" style="cursor:pointer;"><h3><?php echo CASHIER_MANAGER ;?></h3></a>
			</div>
			<a onclick="paymentStatus('cm');" style="cursor:pointer;"><img border="0" src="images/cashiermanager.jpg"></a>
		</div>
		
		<div class="manage_box" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a onclick="paymentStatus('rb');" style="cursor:pointer;"><h3><?php echo RESTAURANT_BOOKING_MANAGER ;?></h3></a>
			</div>
			<a onclick="paymentStatus('rb');" style="cursor:pointer;"><img border="0" src="images/resbookingmanager.jpg"></a>
		</div>
		
		<div class="manage_box" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a onclick="paymentStatus('tb');" style="cursor:pointer;"><h3><?php echo TIMESLOT_BOOKING_MANAGER ;?></h3></a>
			</div>
			<a onclick="paymentStatus('tb');" style="cursor:pointer;"><img border="0" src="images/tsbookingmanager.jpg"></a>
		</div>
		
		<div class="manage_box no_margin" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a onclick="paymentStatus('as');" style="cursor:pointer;"><h3><?php echo APPOINTMENT_SCHEDULE_MANAGER ;?></h3></a>
			</div>
			<a onclick="paymentStatus('as');" style="cursor:pointer;"><img border="0" src="images/appointment.jpg"></a>
		</div>
		
		<div class="manage_box" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a href="loyalty_card.php" style="cursor:pointer;"><h3><?php echo LOYALTY_CARD;?></h3></a>
			</div>
			<a href="loyalty_card.php" style="cursor:pointer;"><img border="0" src="images/loyaltyCard.jpg"></a>
		</div>
		
		<div class="manage_box no_margin" style="margin-top: 20px;">
			<div class="manage_box_hdr">
				<a href="marketing_tool.php"><h3><?php echo MARKETING_TOOL ;?></h3></a>
			</div>
			<a href="marketing_tool.php"><img border="0" src="images/emailMarketing.jpg"></a>
		</div>
		
		<div class="clear"></div>
		
		<div class="userdashboard_ursites">
		<h5 style="color:#ec7923;font-family:'Comfortaa';"><?php echo MY_SITES ;?></h5>
		<table width="100%"  border="0" cellspacing="1" cellpadding="0" id="tblSiteList">
		  <tr>
			<th scope="col" align="left" valign="top"><?php echo DASHBOARD_SITE_NAME ;?></th>
			<th scope="col" align="left" valign="top"><?php echo DATE_CREATED ;?></th>
			<th scope="col" align="left" valign="top"><?php echo STATUS ;?></th>
			<th scope="col" align="left" valign="top" colspan="2"><?php echo OPERATIONS ;?></th>
			<th scope="col" align="left" valign="top"><?php echo PREVIEW ;?></th>
		  </tr>
		      <?php
                                // loop and display the limited records being browsed
                                if(mysql_num_rows($rs)>0){
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) {
                                
                               ?>
		  <tr>
			<td align="left" valign="top"><a href="javascript:clickEdit('<?php echo $arr["nsite_id"]?>');"><?php echo stripslashes($arr["vsite_name"]);?></a></td>
			<td align="left" valign="top"><?php echo stripslashes($arr["ddate"]) ;?></td>
			<td align="left" valign="top"><?php echo  $arr["status"];?> </td>
			<td align="left" valign="top"><a href="javascript:clickEdit('<?php echo $arr["nsite_id"];?>');"><?php echo SM_EDIT;?></a></td>
			<td align="left" valign="top"><a href="#" onClick="javascript:clickDelete('<?php echo $arr["nsite_id"];?>','<?php echo $arr["vsite_name"];?>');" style="text-decoration:none;"><?php echo SM_DELETE;?></a></td>
			<td align="left" valign="top"><a href='workarea/sites/<?php echo $arr[nsite_id];?>/index.html' target='_blank'><?php echo HOME_PREVIEW;?></a></td>
		  </tr>
		  <?php } }
			else 
				echo '<tr><td colspan="6">Sorry! No records Found.</td></tr>';
				
				
				if($totalrows > $pageCount){ 
					echo '<tr><td colspan="6"><span style="float:right"><a href="sitemanager.php">view all</a></span></td></tr>';
				}
		  ?>
		  
		</table>

	</div>		
		
	</div>
	<?php
		$siteUrl = $_SERVER['SERVER_NAME'];
		$paymentUrl = "http://".$siteUrl."/gatewayPayment/payment.php"; 
	?>
	<div id="divWhiteBackground" onclick="onClosePayment( )" style="display:none;position:fixed; left: 0px; top: 0px; right: 0px; bottom: 0px; background:rgba( 250, 250, 250, 0.5 );"></div>
	<div id="divPayment" style="display:none;position: fixed; top:0px; left: 0px; right: 0px; bottom: 0px; z-index: 1000;">
		<iframe src="" id="iframePayment" style="width: 100%;height:100%;" frameborder="0" ></iframe>
		<!-- div onclick="onClosePayment( )" style="float:right; margin-right: 50px; margin-top: 10px; width: 120px; height: 35px; background:#3498db; line-height: 35px; text-align:center;color:#FFF; cursor: pointer;">Close</div  -->
	</div>
	<?php 
	if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}
	// $sql="SELECT nsite_id, nuser_id, vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate FROM tbl_site_mast  WHERE nuser_id='".$_SESSION["session_userid"]."' And vdelstatus !='1'" . $qryopt . "  order by ddate DESC   ";
$sql = "SELECT nsite_id,ncat_id,ntemplate_id,ntheme_id,vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate, CASE is_published WHEN 1 THEN 'Published' WHEN 0 THEN 'Draft' END  as 'status',vtype
        FROM tbl_site_mast WHERE nsite_id IS NOT NULL AND ndel_status='0' AND nuser_id='" . $_SESSION["session_userid"] . "' " . $qryopt1 . " ORDER BY ddate DESC ";
$session_back = "sitemanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$gbackurl = $session_back;
// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$pageCount = 5;
$navigate = pageBrowser($totalrows, 5, $pageCount, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
	?>
	
	

<form name="frmSites" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">
<input type="hidden" name="siteId" id="siteId" value="">
<input type="hidden" name="siteName" id="siteName" value="">
	
	</form>

	
<div class="clear"></div>
</div>
<script language="JavaScript" type="text/JavaScript">
function onAfterPayment( ){
	$("#divWhiteBackground").fadeOut( );
	$("#divPayment").fadeOut( );
	$("body").css("overflow", "inherit" );
}
function onClosePayment( ){
	$("#divWhiteBackground").fadeOut( );
	$("#divPayment").fadeOut( );
	$("body").css("overflow", "inherit" );
}
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
var isAjax = false;
function paymentStatus( planGroupCode ){
	var userId = $("#userId").val( );
	if( isAjax == true ) return;
	isAjax = true;
	$.ajax({
        url: "marketing/async-getPaymentStatus.php",
        dataType : "json",
        type : "POST",
        data : { userId : userId, planGroupCode : planGroupCode },
        success : function(data){
        	isAjax = false;
            if(data.result == "success"){
            	if( data.status == 0 ){
                	var url = "<?php echo $paymentUrl?>?userId=" + userId + "&planGroupCode=" + planGroupCode + "&accountCode=" + data.accountCode;
                	if( planGroupCode == "cm" ){
                    	$("#iframePayment").attr("src", url );
                	}else if( planGroupCode == "rb" ){
                		$("#iframePayment").attr("src", url );
                	}else if( planGroupCode == "tb" ){
                		$("#iframePayment").attr("src", url );
                	}else if( planGroupCode == "as" ){
                		$("#iframePayment").attr("src", url );
                	}
                	$("#divWhiteBackground").fadeIn( );
                	$("#divPayment").fadeIn( );
                	$("body").css("overflow", "hidden" );                	
            	}else if( data.status == 1 ){
                	if( planGroupCode == "cm" ){
                    	window.location.href = 'cashiermanager.php';
                	}else if( planGroupCode == "rb" ){
                		window.location.href = 'res_booking_manager.php';
                	}else if( planGroupCode == "tb" ){
                		window.location.href = 'timeslot_booking_manager.php';
                	}else if( planGroupCode == "as" ){
                		window.location.href = 'appointment_schedule_manager.php';
                	}
                    	
            	}
            }
        }
    });
}
function clickEdit(siteid)
{
        document.frmSites.postback.value="E";
        document.frmSites.action="edit_site_intermediate.php?action=editsite&siteid=" + siteid;
        //document.frmSites.action="editor.php?actiontype=editsite&siteid=" + siteid;
        document.frmSites.method="post";
        document.frmSites.submit();
}

function clickDelete(siteid,sitename){ 
    if(confirm("<?php echo VAL_DELETE;?>")) {
        var frmId = document.frmSites;
        frmId.postback.value="D";
        frmId.siteId.value=siteid;
        frmId.siteName.value=sitename;
        frmId.action="sitemanager.php";
        frmId.method="post";
        frmId.submit();
    }
}
 
</script>
<div class="greyDivider"></div>
<?php include "includes/footArea.php"; ?>
<?php
// include "includes/userfooter.php";
?>
</body>
</html>