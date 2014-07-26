<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              	  |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/zipdirectoryclass.php";
include "includes/sitefunctions.php";

$siteid 		= $_SESSION['siteDetails']['siteId'];
$templateid             = $_SESSION['siteDetails']['siteInfo']['templateid'];
$userid 		= $_SESSION["session_userid"];
$sitename = $_SESSION['siteDetails']['siteInfo']['siteName'];

if($siteLanguageOption=="english"){
    $siteNameModified = getAlias($sitename,'-');
}else{
    $siteNameModified = $siteid;
}

$userDetails = getUserDetails($userid);

$paymentStatus = getPaymentStatusByUser($siteid,$userid);
//echopre($paymentStatus);

// added for correction while redirecting
$rootserver 	= $_SESSION["session_rootserver"];
$secureserver 	= $_SESSION["session_secureserver"];

if($paymentStatus <= 0) {
    echo("<script>alert('".DOWNLOAD_PUBLISH_WRONGSITE."'); location.href=\"usermain.php\";</script>");
    exit;
}

$errormessage = "";
/*check whether the site exist in tbl_site_mast with  the  combination of current templateid,userid 
 if site exist in database  check for the pages exist in workarea location
*/ 
$qry = "select * from tbl_site_mast where nsite_id='" . $siteid . "' and nuser_id='" . $userid . "' and ntemplate_id='" . $templateid . "'"; 
if (mysql_num_rows(mysql_query($qry)) > 0) {
    if (! is_dir(USER_SITE_UPLOAD_PATH.$siteid)) {
        $errormessage = DOWNLOAD_SITE_REMOVED;
    }
} else {
    $errormessage = DOWNLOAD_PUBLISH_AUTHORIZATION;
}

/*
if ($errormessage != "") {
    echo $errormessage;
    exit;
} */

/*
if ($_SESSION['session_paymentmode'] != "success") {
    header("location:payment.php");
    exit;
} */

/* Get the download type that are permitted by admin(ie ftp,zip or both) */

$ftpzip = getSettingsValue('user_publish_option'); 
switch($ftpzip){
    case 'FTP/ZIP/SUBFOLDER':
         $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY1;
         break;
    case 'FTP/ZIP':
         $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY2;
         break;
    case 'FTP/SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY3;
        break;
    case 'ZIP/SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY4;
        break;
    case 'FTP':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY5;
        break;
    case 'ZIP':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY6;
        break;
    case 'SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY7;
        break;
}

$ftp_preset = getSettingsValue('enable_ftp_preset');
 
$tempFolder = explode("/",$_SESSION['replacepath']); 
$arrayIndex=sizeof($tempFolder)-3;

$_SERVER['HTTP_REFERER'] = "frompayment";
include "includes/userheader.php";
?>
<SCRIPT>
    function showpreview(){ 
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="editor_sitepreview.php";
        insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);

    }
    function onAddDomain( ){
    	var actionType = $('input[name="actionType"]:checked').val();
    	if( actionType == "buy" ){
    		onCreateDomain( );
    	}else if( actionType == "transfer" ){
    		onTransferDomain( );
    	}else{
        	alert("You have to select BUY/TRANSFER Domain");
        	return;
    	}
    }
    function onTransferDomain( ){
    	getPaymentUrl( );
    }
    
    function onCreateDomain( ){
        var domainName = $("#domainName").val();
        if( domainName == "" ){
            alert("Please input domain.");
            return;
        }
    	$.ajax({
            url: "marketing/async-isValidDomain.php",
            dataType : "json",
            type : "POST",
            data : { domainName : domainName },
            success : function(data){
                if(data.result == "success"){
                	getPaymentUrl( );
                }else{
                    alert("You can't create the Domain with this name");
            		$("#buttonArea").fadeIn();
            		$("#uploadingArea").fadeOut();                    
                }
            }
        });
	}
	function getPaymentUrl(){
		var ownerId = $("#ownerId").val( );
		var siteId = $("#siteId").val( );
    	$.ajax({
            url: "marketing/async-getPaymentUrl.php",
            dataType : "json",
            type : "POST",
            data : { ownerId : ownerId, siteId : siteId },
            success : function(data){
                if(data.result == "success"){                  
                    $("#iframePayment").attr("src", data.paymentUrl );
                	$("#divWhiteBackground").fadeIn( );
                	$("#divPayment").fadeIn( );
                	$("body").css("overflow", "hidden" );
                }
            }
        });
	}

	function fnCreateDomain( ){
		$("#buttonArea").fadeOut();
		$("#uploadingArea").fadeIn();  		
		$("#divWhiteBackground").fadeOut( );
		$("#divPayment").fadeOut( );
		$("body").css("overflow", "inherit" );		
		var domainName = $("#domainName").val();
		var ownerId = $("#ownerId").val( );
		var siteId = $("#siteId").val( );
    	$.ajax({
            url: "marketing/async-createDomain.php",
            dataType : "json",
            type : "POST",
            data : { domainName : domainName, ownerId : ownerId, siteId : siteId },
            success : function(data){
                if(data.result == "success"){
                    alert("We will email to you when the Web Site is built.");
            		$("#buttonArea").fadeIn();
            		$("#uploadingArea").fadeOut();
            		$("#btnContinue").hide();    					
                }else{
            		$("#buttonArea").fadeIn();
            		$("#uploadingArea").fadeOut();                       
                }
            }
        });		
	}
	function fnTransferDomain( ){
		$("#buttonArea").fadeOut();
		$("#uploadingArea").fadeIn();  		
		$("#divWhiteBackground").fadeOut( );
		$("#divPayment").fadeOut( );
		$("body").css("overflow", "inherit" );		
		var domainName = $("#domainName").val();
		var ownerId = $("#ownerId").val( );
		var siteId = $("#siteId").val( );
    	$.ajax({
            url: "marketing/async-transferDomain.php",
            dataType : "json",
            type : "POST",
            data : { domainName : domainName, ownerId : ownerId, siteId : siteId },
            success : function(data){
                if(data.result == "success"){
                    alert("We will email to you when the Web Site is transfered.");
            		$("#buttonArea").fadeIn();
            		$("#uploadingArea").fadeOut();
            		$("#btnContinue").hide();    					
                }else{
            		$("#buttonArea").fadeIn();
            		$("#uploadingArea").fadeOut();                       
                }
            }
        });		
	}	
	function onAfterPayment( ){
    	var actionType = $('input[name="actionType"]:checked').val();
    	if( actionType == "buy" ){
    		fnCreateDomain( );
    	}else if( actionType == "transfer" ){
    		fnTransferDomain( );
    	}		
	}
	function onClosePayment( ){
		$("#divWhiteBackground").fadeOut( );
		$("#divPayment").fadeOut( );
		$("body").css("overflow", "inherit" );
	}
	function onEditWebsite( ){
		var siteId = $("#siteId").val( );
    	$.ajax({
            url: "marketing/async-updateSite.php",
            dataType : "json",
            type : "POST",
            data : { siteId : siteId },
            success : function(data){
                if(data.result == "success"){
                    alert("We will email to you when the Web Site is updated.");
                }
            }
        });			
	}
</SCRIPT>
<style>

.tooltip {color: #929292;
    display: inline-block;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
    font-weight: normal;
    line-height: 20px;
    padding: 0 0 0 5px;
    text-align: left;font-style:italic;}
</style>
<input type="hidden" value="<?php echo $userid?>" id="ownerId"/>
<input type="hidden" value="<?php echo $siteid?>" id="siteId"/>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align="center">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                    <?php if(isset($_GET['status']) && $_GET['status']=='done')
                    		echo ' <h3 style=" color: #0EB9AF; font-family: dosis,arial;  ; font-weight: normal; margin: 20px 0; padding: 0;">Successfully published your site</h3>';
                    	else
                    		 echo '<h2>'.DOWNLOAD_PUBLISHED.'</h2>'
                    ?>
                       
                    </td>
                </tr>
                <tr>
                    <td class=errormessage><?php echo $errormessage; ?></td>
                </tr>
				<?php 
				$sql = "select * from tbl_domain_info where siteId='$siteid'";
				if (mysql_num_rows(mysql_query($sql)) > 0) {
					$type = "edit";
				} else {
					$type = "add";
				}
				?>                
                <?php if($type == "add"){ ?>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <!-- Main section starts here-->
                        <div>
                            <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">
                            	<tr>
				                    <td  class="maintext">
				                        <input type="radio" name="actionType" value="buy"/><span>I want to BUY Domain</span>
				                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				                        <input type="radio" name="actionType" value="transfer"/><span>I want to TRANSFER Domain</span>
				                    </td>
                            	</tr>
                                <tr>
                                    <td class=maintext align=left>
                                      <input type="text" value="" id="domainName" name="domainName" placeholder = "Domain Name" style="width:250px;height:30px;padding:3px;"/>
                                      <span>&nbsp;(&nbsp;ie : www.google.com, www.dnsimple.com&nbsp;)</span>
                                      <p style="padding-top:5px;">
                                      <span>The email will be sent when your request has been done successfully. </span>
                                      </p> 
                                    </td>
                                </tr>

                                <tr id="buttonArea">
                                    <td ><br>&nbsp;
                                        <input class="grey-btn02" type="button" name=btnback value="<?php echo TEMPLATE_SAVE;?>" onclick="window.location='editor.php'"> &nbsp;
                                        <input class=btn04 onclick="onAddDomain( )" style="width: 130px;" id="btnContinue" name=submitdownload value="<?php echo TEMPLATE_CONTINUE;?>">
                                        <input class=grey-btn02 type=button name=btnpreviews value="<?php echo TEMP_PREVIEW;?>" onclick="showpreview();" >
                                    </td>
                                </tr>
                                <tr id="uploadingArea" style="display:none;">
                                	<td><h3>Uploading Now</h3></td>
                                </tr>
                            </table>
                        </div>
                        <!-- Main section ends here-->
                    </td>
                </tr>
               <?php }else{ ?>
                <tr>
                    <td  class="maintext">
                        <h3>Update Your Website</h3>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr id="buttonArea">
                	<td ><br>&nbsp;
                    	<input class="grey-btn02" type="button" name=btnback value="<?php echo TEMPLATE_SAVE;?>" onclick="window.location='editor.php'"> &nbsp;
                        <input class=btn04 onclick="onEditWebsite( )" style="width: 130px;" id="btnContinue" name=submitdownload value="<?php echo TEMPLATE_CONTINUE;?>">
                        <input class=grey-btn02 type=button name=btnpreviews value="<?php echo TEMP_PREVIEW;?>" onclick="showpreview();" >
                    </td>
                </tr>                
                <?php } ?>               
               
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>
    
	<?php
		$siteUrl = $_SERVER['SERVER_NAME'];
		$paymentUrl = "http://".$siteUrl."/gatewayPayment/paymentSiteManager.php?accountCode="; 
	?>
	<div id="divWhiteBackground" onclick="onClosePayment( )" style="display:none;position:fixed; left: 0px; top: 0px; right: 0px; bottom: 0px; background:rgba( 250, 250, 250, 0.5 );"></div>
	<div id="divPayment" style="display:none;position: fixed; top:0px; left: 0px; right: 0px; bottom: 0px; z-index: 1000;">
		<iframe src="" id="iframePayment" style="width: 100%;height:100%;" frameborder="0" ></iframe>
	</div>
<?php
include "includes/userfooter.php";
?>