<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish<girish@armia.com>                                  |
// |                                                                                                      // |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

if (get_magic_quotes_gpc()) {
    $_POST 		= array_map('stripslashes_deep', $_POST);
    $_GET 		= array_map('stripslashes_deep', $_GET);
    $_COOKIE 	= array_map('stripslashes_deep', $_COOKIE);
}

$message_delete 	= "";
$sitetype	 	= isset($_POST["siteType"])	?	$_POST["siteType"]	:	"";
$template_type 	= isset($_POST["tempType"])	?	$_POST["tempType"]	:	"";
$tmpsiteid 		= isset($_POST["siteId"])	?	$_POST["siteId"]	:	"";
$userid 		= isset($_POST["userId"])	?	$_POST["userId"]	:	"";
$tempid 		= isset($_POST["tempId"])	?	$_POST["tempId"]	:	"";
$cc_type		= isset($_POST['ptype'])	?	$_POST['ptype']		:	"";
$cc_tran		= isset($_POST['txnid'])	?	$_POST['txnid']		:	"";

//#A2 starts here
//set amount for publishing
$qry	= "select vname,vvalue from tbl_lookup where vname IN('site_price','naff_amnt')";
$result		= mysql_query($qry);
while($row = mysql_fetch_array($result)) {
	switch($row["vname"]) {
		case site_price:
				$cost		  = (int)$row["vvalue"];
				break;
		case naff_amnt:
				$var_aff_amnt = (int)$row["vvalue"];
				break;
	}
}
//#A2 ends here
//#A1 starts here
// the below code is to enable administrator to make a site as PAID
if($_POST["PF"] == "PF") {
	//payment flag = default to false
	$bool_payment	=	false;
	
	//check for payment data for the temporary site id in payment table
	// if true then message is shown saying 'payment over for site'
	if ($sitetype == "new")
		$sql = "Select * from tbl_payment where ntempsite_id='" . $tmpsiteid . "'";
	else
		$sql = "Select * from tbl_payment where nsite_id='" . $tmpsiteid . "'";
	
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0) {
			$bool_payment	= true;
			$message_delete	= "This site is already paid!";
	} else {
		$sql = "select * from tbl_tempsite_mast where ntempsite_id='".addslashes($tmpsiteid)."'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if(! is_dir("../workarea/tempsites/".$tmpsiteid)){
			  $message_delete = "Site temporary removed. Please contact administrator to resolve issue!";
			  
			} elseif ($row["nuser_id"] != $userid) {
			  $message_delete = "Incorrect user id as input. Please do not manipulate the url.";
			  
			} else {
			
				$sql	= "SELECT * FROM tbl_payment WHERE vtxn_id = '{$cc_tran}' 
						  				 AND vpayment_type = '{$cc_type}'";

				$res	= mysql_query($sql);
				if (mysql_num_rows($res) > 0) {
				  $message_delete = "Transaction ID already exits!";
				} else {
					$Cust_ip	= getClientIP();
					$Cust_id	= uniqid("Cust");
					$var_id	= process_after_payment($template_type, $tmpsiteid, $userid,
													$cost, $var_aff_amnt, $Cust_id, $cc_tran, $cc_type);
					
					if ($var_id > 0)
					  $message_delete = "Successfully made as Paid!";
				}
			}
		}
	}
	echo "<script language='javascript'>location.href='sitemanager.php?msg=$message_delete';</script>";
}

function process_after_payment($template_type, $tmpsiteid, $userid, $cost, $var_aff_amnt, $Cust_id, $cc_tran = "GCO", $type="GoogleCO") {
    $var_id	= "0";
	$sql =  "Insert Into tbl_site_mast(vsite_name,nuser_id,ntemplate_id,vtype,vtitle,
    vmeta_description,vmeta_key,vlogo,vcompany,vcaption,vlinks,vcolor,vemail,
    ddate,vdelstatus,vsub_logo,vsub_caption,vsub_company,vsub_sitelinks) Select vsite_name,nuser_id,ntemplate_id,vtype,vtitle,vmeta_description,
    vmeta_key,vlogo,vcompany,vcaption,vlinks,vcolor,vemail,ddate,vdelstatus,vsub_logo,vsub_caption,vsub_company,vsub_sitelinks
    from tbl_tempsite_mast where ntempsite_id='" . $tmpsiteid . "'";
    mysql_query($sql) or die(mysql_error());
    $var_id = mysql_insert_id();

    $sql ="Insert Into tbl_site_pages(nsite_id,vpage_name,vpage_title,vpage_type,vtype)
    Select '" . $var_id . "' as 'nsite_id' ,vpage_name,vpage_title,vpage_type,vtype
    from tbl_tempsite_pages where ntempsite_id='" .  $tmpsiteid  . "' order by ntempsp_id";
    mysql_query($sql) or die(mysql_error());

    $sql = "Insert into tbl_payment(npayment_id,ntempsite_id,nsite_id,nuser_id,namount,ddate,
    vpayment_type,vtxn_id,vuniqid) Values('',
    '" . $tmpsiteid . "',
    '" . $var_id . "',
    '" . $userid . "',
    '" . $cost . "',
    now(),
    '".$type."',
    '" . $cc_tran . "',
    '" . $Cust_id . "')";
    mysql_query($sql) or die(mysql_error());
    //pay affiliate
    payaffiliate($userid,$var_aff_amnt);
    if(!is_dir("../sites/$var_id")) {
                    @mkdir("../sites/$var_id",0777);
                    @chmod("../sites/$var_id",0777);
    }
    $sql = "Delete from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "'";
    mysql_query($sql) or die(mysql_error());
    $sql = "Delete from tbl_tempsite_mast where ntempsite_id='" . $tmpsiteid . "'";
    mysql_query($sql) or die(mysql_error());
    //$_SESSION['session_currenttempsiteid']= $var_id;
    //$_SESSION['session_paymentmode']="success";
    if($template_type == "advanced") {
        copyfilesdirr("../workarea/tempsites/".$tmpsiteid,"../sites/$var_id",0777,false);
        remove_dir("../workarea/tempsites/".$tmpsiteid);
        remove_dir("../sitepages/tempsites/".$tmpsiteid);
        //header("location:$rootserver/postpublish.php?sid=" . $var_id);
        //$redirecturl = "$rootserver/postpublish.php?sid=" . $var_id;
    } else {
        copydirr("../sitepages/tempsites/$tmpsiteid/","../sites/$var_id",0777,false);
        //copy resource.txt
        copy("../workarea/tempsites/".$tmpsiteid."/resource.txt","../sites/$var_id/resource.txt");
        chmod("../sites/$var_id/resource.txt",0777);
        remove_dir("../workarea/tempsites/$tmpsiteid");
	// the belwo 2 lines are commented to avoid unncessary deletion of sites
	//remove_dir("./workarea/sites/$var_id");
	//remove_dir("./sitepages/tempsites/$var_id");
	    remove_dir("../sitepages/tempsites/$tmpsiteid");
        //header("location:$rootserver/downloadsite.php?sid=" . $var_id);
        //$redirecturl = "$rootserver/downloadsite.php?sid=" . $var_id;
    }
    return $var_id;
}

function payaffiliate($userid, $var_aff_amnt) {
	$sql = "Select naff_id from tbl_user_mast where nuser_id = '" . addslashes($userid) . "'";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		if($row["naff_id"] != "0" && $row["naff_id"] != "") {
			$sql = "Select nsite_id from tbl_site_mast where nuser_id='" . addslashes($userid) . "'";
			if(mysql_num_rows(mysql_query($sql)) == "1") {
				$sql = "Insert into tbl_aff_ref_txns(nid,n_aff_id,nuser_id,ddate,namount,vsettled_status) Values('',
						'" . $row["naff_id"] . "',
						'" . $userid . "',
						now(),
						'" . $var_aff_amnt . "','0')";
				mysql_query($sql) or die(mysql_error());
			}
		}
	}
}

//#A1 ends here 
?>


<script language="JavaScript" type="text/JavaScript">
function validate(){
	var frm = document.settingsForm;

	var error = false;
	var message = "";
	
	if(frm.ptype.value.length==0){
		error = true;
		message += "* Please select payment method!" + "\n";
	}
	if(frm.txnid.value.length==0){
		error = true;
		message += "* Transaction ID is empty!" + "\n";
	}
	if(error){
		message = "Errors found!" + "\n" + message;
		alert(message);
		return false;
	} else {
		document.settingsForm.submit();
	}
}

function isNumeric(sText)
{

   var IsNumber=true;
   var Char;



   for (i = 0; i < sText.length; i++)
      {
      Char = sText.charAt(i);
      if (Char != "1" && Char != "2" && Char != "3" && Char != "4" && Char != "5" && Char != "6" && Char != "7" && Char != "8" && Char != "9" && Char != "0")
         {
         IsNumber = false;
         }
      }

   return IsNumber;

}

function isNumber(val){
	if((val.indexOf("e") != -1 ) || (val.indexOf("E") != -1 )){
		return false;
	}
	if(isNaN(val)){
		return false;
	}
	return true;
}

</script>
<?php
$sql = "SELECT * FROM tbl_lookup";
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
    $vname	= stripslashes($row["vname"]);
    $vvalue	= stripslashes($row["vvalue"]);

	switch($vname){
			case  admin_mail:
			 $admin_mail=$vvalue;
			 break;
                         case  theme:
			 $theme=$vvalue;
			 break;
			case  user_max_storage:
			 $user_max_storage=$vvalue;
			 break;

			case  user_publish_option:
			 $user_publish_option=$vvalue;
			 break;

			case  day_maintain_temp:
			 $day_maintain_temp=$vvalue;
			 break;

			case  site_name:
			 $site_name=$vvalue;
			 break;

			case  site_price:
			 $site_price=$vvalue;
			 break;

			case  root_directory:
			 $root_directory=$vvalue;
			 break;

			case  auth_txnkey:
			 $auth_txnkey=$vvalue;
			 break;

			case  auth_loginid:
			 $auth_loginid=$vvalue;
			 break;

			case  auth_email:
			 $auth_email=$vvalue;
			 break;

			case  auth_pass:
			 $auth_pass=$vvalue;
			 break;

			case  auth_demo:
			 $auth_demo=$vvalue;
			 break;

			case  secureserver:
			 $secureserver=$vvalue;
			 break;

			case  Logourl:
			 $Logourl=$vvalue;
			 break;

			case  paymentsupport:
			 $paymentsupport=$vvalue;
			 break;

			case  enable_paypal:
			 $enable_paypal=$vvalue;
			 break;

			case  paypal_sandbox:
			 $paypal_sandbox=$vvalue;
			 break;

			case   paypal_email:
			 $paypal_email=$vvalue;
			 break;

			case   checkout_demo:
			 $checkout_demo=$vvalue;
			 break;

			case   checkout_key:
			 $checkout_key=$vvalue;
			 break;

			case checkout_productid:
			 $checkout_productid=$vvalue;
			 break;

			case   enable_gateways:
			 $enable_gateways=$vvalue;
			 break;

			case   rootserver:
			 $rootserver=$vvalue;
			 break;

			case   paypal_token:
			 $paypal_token=$vvalue;
			 break;

			case enable_google:
				$enable_google	= $vvalue;
			break;

			case google_demo:
				$google_demo	= $vvalue;
			break;

			case google_id:
				$google_id	= $vvalue;
			break;

			case google_key:
				$google_key	= $vvalue;
			break;

			case linkpay_store:
				$linkpay_store	= $vvalue;
			break;

			case linkpay_demo:
				$linkpay_demo	= $vvalue;
			break;

			case vLicenceKey:
				$txtLicenseKey	= $vvalue;
			break;
	}

}

?>
<table>
<tr>
<td align=center ><img src="../images/sitemanager.gif" ></td>
</tr>
</table>
<form name="settingsForm" method="post" action="sitepayment.php"  ENCTYPE="multipart/form-data">
<fieldset style="width:80%;">
<legend class=maintext>Make As Paid </legend>
<table>

<tr>
<td align=center colspan=3 class=maintext><br><br>
  <input name="postback" type="hidden" id="postback">
  <input name="id" type="hidden" id="id">
  <input type="hidden" name="siteType" id="siteType" value="<?php echo $sitetype?>">
  <input type="hidden" name="tempType" id="tempType" value="<?php echo $template_type?>">
  <input type="hidden" name="siteId" id="siteId" value="<?php echo $tmpsiteid?>">
  <input type="hidden" name="tempId" id="tempId" value="<?php echo $tempid?>">
  <input type="hidden" name="userId" id="userId" value="<?php echo $userid?>">
  <input type="hidden" name="PF" id="PF" value="PF"></td>
</tr>


<tr>
<td align=center colspan=3 class=maintext><font color=red><?php  echo $message_delete;?></font></td>
</tr>

<tr>
<td align=right class=maintext>Select Payment Method </td>
<td></td>
<td align=left>
<select name="ptype"  id="ptype" class="textbox">
    <?php if($enable_google=='YES')
        { ?>
  <option value="GoogleCO" selected>Google Check Out</option>
  
        <?php } if($enable_paypal=='YES')
      { ?>
   <option value="PayPal" selected>PayPal</option>
   <?php } ?>
</select>
</td>
</tr>

<tr>
  <td align=right class=maintext>Enter Transaction ID </td>
  <td></td>
  <td align=left><input type="text" class="textbox" name="txnid" id="txnid" maxlength="100" value="<?php  echo htmlentities($cc_tran); ?>" ></td>
</tr>
<tr>
  <td colspan="3" class=maintext>&nbsp;</td>
  </tr>
<tr>
  <td colspan="3" class=maintext>&nbsp;</td>
</tr>
<tr>
<td  colspan=3 width=100% align=center><br><input type="button" class="button" onClick="validate();" value="Save">&nbsp;&nbsp;<input type=button class="button" value="Back" onClick="location.href='sitemanager.php'"></td>
</tr>

</table>


</fieldset>
</form>
<?php

include "includes/adminfooter.php";

?>