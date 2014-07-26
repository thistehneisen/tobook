<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy	<jimmy.jos@armia.com>        		              |
// +----------------------------------------------------------------------+
// include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch=($_GET["txtSearch"] != ""?trim($_GET["txtSearch"]):trim($_POST["txtSearch"]));
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);


if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}
?>
<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmAffiliates.postback.value="S";
        document.frmAffiliates.action="affiliates.php";
        document.frmAffiliates.submit();
}
</script>

<form name="frmAffiliates" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">
<input name="changeto" type="hidden" id="changeto">
<?php
$qryopt="";
if($txtSearch != ""){
	if($cmbSearchType == "name"){
		$qryopt .= "  WHERE vaff_name like '" . addslashes($txtSearch) . "%'";
	}elseif($cmbSearchType == "email"){
		$qryopt .= "  WHERE vaff_mail like '" . addslashes($txtSearch) . "%'";
	}
}

$sql="SELECT *  FROM tbl_affiliates   " . $qryopt . "  order by naff_id DESC   ";
																//echo $sql;
$sess_back="affiliates.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$_SESSION["session_gbackurl"] = $sess_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/

$navigate = pageBrowser($totalrows,10,10,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql);
?>

<table width="82%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
	<table  width="100%"  border="0" cellspacing="0" cellpadding="0">
    	 <tr>
    		 <td align="center"><img src="../images/affiliatemanager.gif" ><br>&nbsp;</td>
         </tr>
         <tr>
         	<td  class="listingmain">
				<table width="100%"  border="0" cellpadding="2" cellspacing="1" class="maintext">
				<tr class=background>
				<td width="100%">
					<table  border="0" class='text' width="100%">
					<tr>
							<td colspan="4" align="center" width="100%"><?php echo($message_delete); ?></td>
					</tr>
					<tr>
					<td width="3%" align="center" valign="top">&nbsp;</td>
					<td width="30%" valign="bottom"
					class="category"><?php echo("Listing $navigate[1] of $totalrows results.");?>
					</td>
					<td width="67%" valign="top"
					bgcolor="#FFFFFF" class="listing126" align=right>search ::
					<select name="cmbSearchType" class="selectbox">
								 <option value="name"  <?php  if($cmbSearchType == "name" || $cmbSearchType == ""){ echo("selected"); } ?>>Name</option>
								<option value="email"  <?php  if($cmbSearchType == "email" || $cmbSearchType == ""){ echo("selected"); } ?>>Email</option>
								
							   </select> &nbsp;
                                 <input type="text" name="txtSearch" size="20" maxlength="50" value="<?php  echo(htmlentities($txtSearch)); ?>"  onKeyPress="if(window.event.keyCode == '13'){ return false; }" class="textbox">&nbsp;</td><td width="5%" valign="middle" class="listing126"><a href="javascript:clickSearch();">
					<img src='../images/go.gif'  width="20" height="20" border='0'></a></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
				<table width="100%"  border="0" cellpadding="5" cellspacing="1" class="maintext">
				<tr class="blacksub">
						<td width="22%" valign="top">Name</td>
						<td width="22%">Login Name</td>
						<td width="30%">Email</td>
						<td width="19%">Country</td>
						<td width="7%">State</td>
				</tr>
				<?php

						//loop and display the limited records being browsed
						while ($arr = mysql_fetch_array($rs)) {
							$link = "<a href=viewaffiliate.php?id=" . $arr["naff_id"] . ">" . stripslashes($arr["vaff_name"]) . "</a>";
							
							echo "<tr   bgcolor=\"#FFFFFF\"><td style='word-break:break-all;' width=\"20%\">&nbsp;".$link."</td>";
							echo "<td  width=\"20%\" style='word-break:break-all;'>&nbsp;" . $arr["vaff_login"] . "</td>";
							echo "<td  width=\"20%\" style='word-break:break-all;'>&nbsp;<a href='mailto:".stripslashes($arr["vaff_mail"])."'>".stripslashes($arr["vaff_mail"])."</a></td>";
							echo "<td  width=\"20%\">&nbsp;".stripslashes($arr["vaff_country"])."</td>";
							echo "<td  width=\"20%\">&nbsp;".stripslashes($arr["vaff_state"])."</td>";
							
							echo "</tr>";
						}
					?>
				
				<tr class=background>
					<td colspan="7" align="center" height="30">
					<?php echo($navigate[2]); ?>&nbsp;</td>
				</tr>
				</table>
          
         </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
       </table>
</form>
<?php
include "includes/adminfooter.php";
?>