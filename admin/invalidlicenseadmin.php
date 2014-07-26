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
//include files
include "../includes/session.php";
include "../includes/config.php";
include_once "../includes/function.php";
//include "./includes/adminheader.php";
$license_key	=	getLicense();
$message ="";
if ($_POST["btnGo"] == "Submit") {
	$txtAdminPass  = trim($_POST['txtAdminPass']);
	$txtLicenseKey  = trim($_POST['txtLicenseKey']);
	if ($txtLicenseKey != "" && $txtAdminPass != "") {
		$sqlSelect	= "SELECT * FROM tbl_lookup WHERE vvalue='".md5($txtAdminPass)."' AND vname ='admin_pass'";
		$res =	mysql_query($sqlSelect);
		if(mysql_num_rows($res) > 0){	
			if (strlen($txtLicenseKey) == '30') {
				$sql = "UPDATE tbl_lookup SET vvalue='" . addslashes($txtLicenseKey) . "' where vname = 'vLicenceKey'";
				mysql_query($sql);
				header("Location:index.php");
				exit;
			}else
				$message = "Invalid key. Please enter a valid key";
		}else
			$message = "Invalid admin password. Please enter a valid admin password";
	}else
		$message = "Please enter new key";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<TITLE><?php echo getSettingsValue('site_name') ?> - The do it yourself online website builder</TITLE>
<META name="description" content="<?php echo getSettingsValue('site_name') ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<link href="<?php echo $currentloc?>style/<?php echo $_SESSION["session_style"];?>" TYPE="text/css" REL=STYLESHEET>
<script>
history.forward();
</script>
<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">
<script language="javascript1.1" type="text/javascript">
// Finction to show license key entry textbox...
function enterNewKey(){
	document.getElementById('adminpass').style.display = '';
	document.getElementById('licensekey').style.display = '';
}
 --> 
</script>
<script language="javascript1.1" type="text/javascript">
function emptyCheck()
{
	if(document.frmLicense.txtAdminPass.value == ""){
		alert('Please enter administrator password');
		document.frmLicense.txtAdminPass.focus();
		return false;	
	}else if(document.frmLicense.txtLicenseKey.value == ""){
		alert('Please enter valid license key');
		document.frmLicense.txtLicenseKey.focus();
		return false;	
	}	
}
</script>
</head>
<body  topmargin="0">
<table width="802" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#05B5FE">
	<tr>
		<td align="center">
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
	   		<tr>
    			<td>
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            		<tr>
              		<td style="background: url(../images/ecinnerheader.gif);BACKGROUND-REPEAT: no-repeat;" width="800" height="84" border="0" align="left" valign="top"><a href="../index.php"><img src ="../<?php echo($_SESSION["session_logourl"]); ?>" border=0></a></td>
            		</tr>
          			</table>
            		<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
              		<tr>
                	<td align=center>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
							<td bgcolor="#E41E4F"><img src="../images/spacer1.gif" width="1" height="1"></td>
							</tr>
						</table>	  
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
							<td align="left" bgcolor="#FFFFFF"><img src="../images/ecredbarflip.gif" width="427" height="6"></td>
							</tr>
						</table>									
					</td>
				</tr>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" border=0>
				<tr>
				<td align="center" valign="top" bgcolor="#FFFFFF">
				<!-- START  editable area --------------->
					<form name=frmLicense method=post action="<?php echo $_SERVER["PHP_SELF"];?>" onSubmit="return emptyCheck();">
						<table width="100%"  border="0">
							<tr>
								<td width="41%">&nbsp;</td>
								<td width="28%" align="center" class="bigTitleText"><b>INVALID LICENSE</b></td>
								<td width="30%">&nbsp;</td>
						  	</tr>
						  	<tr><td colspan="3">&nbsp;</td></tr>
							<tr><td align="center" colspan="3"><font color=red><?php echo $message;?></font></td></tr>
						  	<tr><td colspan="3">&nbsp;</td></tr>
						  	<tr>
								<td align="center" valign="top" colspan="3">
									<p align=center>
										<b>Invalid License Key (<?php echo $license_key;?>).<br><br> Please contact support@iscripts.com</b>
									</p>
								</td>
								<td width="1%">&nbsp;</td>
							</tr>
							<tr>
								<td align="center" class="maintext" colspan="3">Click <a href="javascript: enterNewKey();" class="listing">here</a> to enter new key
								</td>
							</tr>
							<tr><td colspan="3">&nbsp;</td></tr>
							<tr id="adminpass" style="display:none;">
								<td width="41%" align="right" class="maintext">Admin password&nbsp;&nbsp;</td>
								<td width="28%" align="left">
									<input name="txtAdminPass"  id="txtAdminPass" type="text" class="textbox" size="25" maxlength="40" value="<?php echo htmlentities($txtAdminPass);?>">
								</td>
								<td width="30%" align="left">&nbsp;</td>
							</tr>
							<tr id="licensekey" style="display:none;">
								<td width="41%" align="right" class="maintext">Enter new license key&nbsp; </td>
								<td width="28%" align="left">
									<input name="txtLicenseKey"  id="txtLicenseKey" type="text" class="textbox" size="45" maxlength="40" value="<?php echo htmlentities($txtLicenseKey);?>">
								</td>
								<td width="30%" align="left" class="maintext">
									<input type="submit" name="btnGo" value="Submit" class="button">
								</td>
							</tr>
							<tr><td colspan="3">&nbsp;</td></tr>
						</table>
					</form>
<?php
	include "includes/adminfooter.php";
?>