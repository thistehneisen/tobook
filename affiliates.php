<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>              		              |
// +----------------------------------------------------------------------+
//include files
include "includes/session.php";
include "includes/config.php";
	$txtAffLogin = $_POST["txtAffLogin"];
	$txtAffPassword = $_POST["txtAffPassword"];
 	if($txtAffLogin != "") {
		$affpassword = md5($txtAffPassword);
        $sqlaffdetails = "SELECT naff_id,vaff_login ,vaff_pass ,vaff_name FROM tbl_affiliates ";
        $sqlaffdetails .= " WHERE  vaff_login='$txtAffLogin' AND vaff_pass='$affpassword' AND vdelstatus = '0' ";
        $resultaffdetails = mysql_query($sqlaffdetails) or die(mysql_error());
        if(mysql_num_rows($resultaffdetails) != 0){//the username and password matches
			//clear all existing sessions before starting a new affiliate session 
			session_unset();
			session_destroy();
			session_start();

			$rowuser = mysql_fetch_array($resultaffdetails);

			$_SESSION["session_affiliate"]		=	$rowuser["naff_id"];
			$_SESSION["session_affiliatename"]	=	stripslashes($rowuser["vaff_name"]);
			echo"<script>location.href='affiliates/main.php'</script>";
			exit();
        }else{
           $affloginmessage = "Invalid Login! Please Retry!";
        }
	}	
include "includes/subpageheader.php";
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="10">
	<tr>
	  <td width="4%">&nbsp;</td>
	  <td width="93%" align=left><img src="images/redcorner.gif" width="16" height="27"> <img src="images/affiliates.gif"></td>
	  <td width="3%">&nbsp;</td>
	</tr>
  </table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="10">
	<tr>
	  <td width="4%">&nbsp;</td>
	  <td width="93%" class=subtext>
		<?php include("./includes/affiliates.php"); ?>
	  </td>
	  <td width="3%">&nbsp;</td>
	</tr>
  </table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="10">
	<tr>
	  <td width="4%">&nbsp;</td>
	  <td width="93%" align="right"><img src="images/redcornerflip.gif" width="16" height="27"></td>
	  <td width="3%">&nbsp;</td>
	</tr>
  </table>
<?php
include "includes/footer.php";
?>