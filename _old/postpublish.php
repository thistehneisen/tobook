<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/zipdirectoryclass.php";

$rootserver = $_SESSION["session_rootserver"];
$secureserver = $_SESSION["session_secureserver"];
// check if passed values are correct and assign session variables accordingly
$userid = $_SESSION["session_userid"];
if ($_POST["sid"] != "") {
				$sql = "Select * from tbl_payment where nsite_id='" . addslashes($_POST["sid"]) . "' AND nuser_id='" . $userid . "'";
				if (mysql_num_rows(mysql_query($sql)) <= 0) {
								clearSessionForSite();
								echo("<script>alert('Wrong site id in request.  Please contact admin for details.'); location.href=\"$rootserver/usermain.php\";</script>");
								exit;
				} else {
								$_SESSION['session_currenttempsiteid'] = $_POST["sid"];
				} 
}

elseif ($_GET["sid"] != "") {
				$sql = "Select * from tbl_payment where nsite_id='" . addslashes($_GET["sid"]) . "' AND nuser_id='" . $userid . "'";
				if (mysql_num_rows(mysql_query($sql)) <= 0) {
								clearSessionForSite();
								echo("<script>alert('Wrong site id in request.  Please contact admin for details.'); location.href=\"$rootserver/usermain.php\";</script>");
								exit;
				} else {
								$_SESSION['session_currenttempsiteid'] = $_GET["sid"];
				}
}
elseif (mysql_num_rows(mysql_query("Select * from tbl_payment where nsite_id='" . $_SESSION['session_currenttempsiteid'] . "' AND nuser_id='$userid'")) <= 0) {
				clearSessionForSite();
				echo("<script>alert('Wrong site id in request.  Please contact admin for details.'); location.href=\"$rootserver/usermain.php\";</script>");
				exit;
} 
// end check if passed values are correct and assign session variables accordingly
function clearSessionForSite()
{
				$_SESSION['session_templatetype'] = "";
				$_SESSION['session_currenttempsiteid'] = "";
				$_SESSION['session_currenttemplateid'] = "";
				$_SESSION['session_sitename'] = "";
} 

$tmpsiteid = $_SESSION['session_currenttempsiteid'];
$templateid = $_SESSION['session_currenttemplateid'];
$userid = $_SESSION["session_userid"];
$siteid = $_SESSION['session_currenttempsiteid'];
$type = $_GET['type'];
$errormessage = "";

$qry = "select * from tbl_site_mast where nsite_id='" . $tmpsiteid . "' and nuser_id='" . $userid . "'";

if (mysql_num_rows(mysql_query($qry)) > 0) {
				if (! is_dir("./sites/$tmpsiteid")) {
								$errormessage = "Site temporary removed.please contact administrator to resolve issue!";
				} 
} else {
				$errormessage = "You are not authorized to view this page!";
} 
if ($errormessage != "") {
				echo $errormessage;
				exit;
} 

/*if( $_SESSION['session_paymentmode'] !="success"){
          header("location:payment.php");
        exit;
}*/
// check if ftp is permitted
$qry = "select vvalue from tbl_lookup where vname='user_publish_option'";
$result = mysql_query($qry);
$row = mysql_fetch_array($result);
$ftpzip = $row["vvalue"];
$_SESSION['replacepath'];

// This code is for fetching the site id

$tempFolder=explode("/",$_SESSION['replacepath']);
$arrayIndex=sizeof($tempFolder)-3;
if ($_POST['submitdownload'] == "Continue") {
				SaveSitePreview($userid, $templateid, $tmpsiteid, $siteid, "Yes", ".", "preview",$tempFolder[$arrayIndex]);
				if ($_POST['downloadformat'] == "1") { // zip format
								$zipfilename = $_SESSION['session_sitename'];
								$dirlocation = "workarea/sites/$tmpsiteid";
								$directoryloclength = strlen($dirlocation);
								$tt = list_directory("$dirlocation");
								$zipTest = new zipfile(); 
								// $zipTest->add_dir("$dirlocation");
								// $zipTest->add_dir($zipfilename);
								foreach($tt as $key => $value) {
												if (basename($value) != "resource.txt") {
																// $zipTest->add_file($value, $value);
																$value_tostoreinzip = substr($value, $directoryloclength + 1);
																$zipTest->add_file($value, "./" . $value_tostoreinzip); 
																// $zipTest->add_file($value,"./". $value_tostoreinzip);
												} 
								} 

								Header("Content-type: application/octet-stream");
								Header ("Content-disposition: attachment; filename=$zipfilename.zip");
								echo $zipTest->file();
				}
				
				//to upload to a sub directory
	else if($_POST['downloadformat']=="3")
	{
		$sitename=$_SESSION['session_sitename'];
		$dirlocation = "workarea/sites/$siteid";
		
		if(!file_exists($sitename))
		{
			 mkdir($sitename,$_SESSION['SERVER_PERMISSION']);
		}//end if
		
		if(!file_exists($sitename."/images"))
		{
			 mkdir($sitename."/images",$_SESSION['SERVER_PERMISSION']);
		}//end if
		
		if(!file_exists($sitename."/flash"))
		{
			 mkdir($sitename."/flash",$_SESSION['SERVER_PERMISSION']);
		}//end if
			
		$remote_dir = $sitename;
		$log1=copyfilesdirr($dirlocation,$remote_dir,$_SESSION['SERVER_PERMISSION'],false);
		$log2=copyfilesdirr($dirlocation."/images",$remote_dir."/images",$_SESSION['SERVER_PERMISSION'],false);
		$log3=copyfilesdirr($dirlocation."/flash",$remote_dir."/flash",$_SESSION['SERVER_PERMISSION'],false);
		
		header("location:postpublish.php?status=done");
        exit();
	}//end else 
		 else 
		 {
				header("location:ftpsite.php?sitetype=adv");
				exit();
		}//end else
} 
$_SERVER['HTTP_REFERER'] = "frompayment";
include "includes/userheader.php";

?>
<script>
function clickSubmit() {

}
function clickCancel() {

}
function showpreview(id,type){
			//hardcoded
			type="editpage";
           var leftPos = (screen.availWidth-500) / 2;
                   var topPos = (screen.availHeight-400) / 2 ;
                  //var strUrl = "../workarea/" + loc + "/" + id + "/" + pageName;
                   winurl="showfinalpreview.php?type="+type+"&";
                   insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);


            // winname="sitepreview";

                        // window.open(winurl,winname,'');

 }
</script>
<h1 class="main_heading">Publish your site</h1>
<div class="common_box">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
	<table width="100%">
		<tr>
			<td>
				
					<table width="80%"  border="0" cellspacing="0" cellpadding="0">
					 <?php if(isset($_GET['status']) && $_GET['status']=='done')
					{
						echo '<tr class="maintext"><td><b>Your site is successfully published to</b> <br><br><a href="'.LookupDisplay('rootserver').'/'.$_SESSION['session_sitename'].'/" target="_blank" class="maintext"><b>'.LookupDisplay('rootserver').'/'.$_SESSION['session_sitename'].'/</b></a></td></tr>';
					}//end if
					else
					{?>
					<tr>
					<td class=maintext align=center><br>
					<?php if($ftpzip=="FTP/ZIP/SUBFOLDER" ){ ?>
					You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or take it as a zip file which you can upload yourself to your server or store in our server under a directory.
					<?php } if($ftpzip=="FTP/ZIP" ){ ?>
					You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or take it as a zip file which you can upload yourself to your server.
					<?php } if($ftpzip=="FTP/SUBFOLDER" ){ ?>
					You can either upload the file directly to your server by providing the FTP information given to you by the hosting company or store in our server under a directory.
					<?php } if($ftpzip=="ZIP/SUBFOLDER" ){ ?>
					You can either take it as a zip file which you can upload yourself to your server or store in our server under a directory.
					<?php } else if( $ftpzip=="FTP"){?>
					   You can  upload the file directly to your server by providing the FTP information given to you by the hosting company
					<?php } else if( $ftpzip=="ZIP"){ ?>
						You can  take the site files as a zip file which you can upload yourself to your server.
					<?php } else if( $ftpzip=="SUBFOLDER"){ ?>
						You can  take the site files as a subfolder which you can upload yourself to your server.
					<?php } ?><br>&nbsp;
					</td>
					</tr>
					<tr>
					<td>
					<!-- Main section starts here-->
							 <form name="frmDownload" action="" method="POST">
							 <table width="60%" border=0 align=center>
					<?php
										 if($ftpzip=="FTP/ZIP/SUBFOLDER" || $ftpzip=="ZIP" || $ftpzip=="FTP/ZIP" || $ftpzip=="ZIP/SUBFOLDER"){
										 ?>
										 <tr>
										 <td class=maintext align=left>
										 <input type=radio name=downloadformat value="1" checked> Get site as ZIP file
										 </td>
										 </tr>
										 <?php
										 }
										 if($ftpzip=="FTP/ZIP/SUBFOLDER" || $ftpzip=="FTP" || $ftpzip=="FTP/ZIP" || $ftpzip=="FTP/SUBFOLDER"){
										 ?>
										 <tr>
										 <td class=maintext align=left>
										 <input type=radio name=downloadformat value="2"  checked>Upload files to a FTP location 
										 </td>
										 </tr>
										  <?php
										 }
										 if($ftpzip=="FTP/ZIP/SUBFOLDER" || $ftpzip=="SUBFOLDER" || $ftpzip=="FTP/SUBFOLDER" || $ftpzip=="ZIP/SUBFOLDER"){
										 ?>
										 <tr>
										 <td class=maintext align=left>
										 <input type=radio name=downloadformat value="3"  checked>Upload files to a SUBFOLDER location 
										 </td>
										 </tr>
										  <?php
										 }
										 ?>
										 <tr>
										
					
					
											   <td colspan=2 align=center><br>&nbsp;
												   <input class=button type=submit name=submitdownload value="Continue">
												   <input class=button type=button name=btnpreviews value="Preview" onclick="showpreview(this.id,'<?php echo $type; ?>');" >
											  
											  </td>
											 </tr>
									 </table>
									 </form>
					<!-- Main section ends here-->
					</td>
					</tr>
					<?php }//end else?>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					</table>
					
				
			</td>
		</tr>
	</table>	
</td>
</tr>
</table>
</div>
<?php
include "includes/userfooter.php";
?>