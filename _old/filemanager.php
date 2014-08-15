<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";


//check if sitename is valid
function isValidsitename($str)
{
    if (trim($str) !="" ) {
          if ( eregi ( "[^0-9a-zA-Z+-+.]", $str ) ) {
                         return false;
             }else{
                    return true;
         }
    }else{
                return false;
    }

}
if($_POST['subbuild']=="GO"){
   $sitename=$_POST['sitename'];
   if (!isValidsitename($sitename)) {
                $message="Inavlid site name";
   }else{
          $_SESSION['session_sitename']=$_POST['sitename'];
                  header("location:buildtype.php");
                  exit;
            //echo "valid site name";
   }
}
if($begin == ""){
        $begin=0;
        $num=1;
        $numBegin=1;
}
include "includes/userheader.php";
?>


<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmSites.postback.value="S";
        document.frmSites.action="filemanager.php";
        document.frmSites.submit();
}



</script>

<form name="frmSites" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">

<?php




$qryopt="";

$txtSearch=$_POST["txtSearch"];
$cmbSearchType=$_POST["cmbSearchType"];

 if($txtSearch != ""){

        if($cmbSearchType == "site name"){
                $qryopt .= "  AND s.vsite_name like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "date"){
                $qryopt .= "  AND date_format(f.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "file name"){
                $qryopt .= "  AND f.vfile_name like '" . addslashes($txtSearch) . "%'";
        }
}

//query for both sites and temp sites
$sql="SELECT f.nfile_id,f.vfile_name,f.nsite_id,f.vlocation,f.vremote_dir,Date_Format(f.ddate,'%m/%d/%Y') as ddate,s.nsite_id,s.vsite_name FROM tbl_site_mast s,tbl_files f where s.nsite_id=f.nsite_id  AND nuser_id='".$_SESSION["session_userid"]."'" . $qryopt . "  order by ddate DESC";





$session_back="sitemanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$gbackurl = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));



$navigate = pageBrowser($totalrows,10,10,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql);
?>

<h1 class="main_heading">File Manager</h1>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align="center">





                <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
                                
                <tr>
                <td  class="listingmain">
                          <table width="100%"  border="0" cellpadding="2" cellspacing="1" class="maintext">
                          <tr class=background>
                          <td width="100%" >


                                           <table  border="0"><tr>
                                           <td width="3%" align="center" valign="top">&nbsp;</td>
                                           <td width="30%" valign="bottom"
                                           class="category"><?php echo("Listing $navigate[1] of $totalrows results."); ?>
                                           </td>
                                           <td width="67%" valign="top"
                                           class="listing126" align=right>search ::

                                           <select name="cmbSearchType" class="selectbox">
                                           <option value="site name"
                                           <?php if($cmbSearchType == "site name" || $cmbSearchType == ""){
                                           echo("selected"); } ?>>Site Name</option>


<option value="file name"
                                           <?php if($cmbSearchType == "file name" || $cmbSearchType == ""){
                                           echo("selected"); } ?>>File Name</option>

                                           <option value="date"  <?php
                                           if($cmbSearchType == "date" || $cmbSearchType == ""){
                                           echo("selected"); } ?>>Date</option>
                                           </select> &nbsp;
                                           <input type="text" name="txtSearch" size="20" maxlength="50"
                                           value="<?php echo(htmlentities($txtSearch)); ?>"
                                           onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                           class="textbox">&nbsp;</td><td width="5%" valign="middle"  class="listing126"><a href="javascript:clickSearch();">
                                           <img src='./images/go.gif'  width="20" height="20" border='0'></a></td>
                                           </td>
                                           </tr>
                                           </table>


                        </td>
                        </tr>
                        </table>


                        <table width="100%"  border="0" cellpadding="5" cellspacing="1" class="maintext">
                        <tr class="blacksub">
                        <td width="3%" valign="top">#</td>
                        <td width="35%">File Name</td>
                        <td width="15%">Location</td>
                        <td width="15%">Site Name</td>
                        <td width="7%">Date</td>
                        <td width="20%">Check Status</td>
                        <td width="5%">Delete</td>
                        </tr>

                                                <?php

                                                //loop and display the limited records being browsed
                                                $counter=1;
                                                while ($arr = mysql_fetch_array($rs)) {
                                                        echo "<tr   class=\"background\">
                                                        <td style='word-break:break-all;' align='left'
                                                         >&nbsp;".$counter."</td>";
                                                        echo "<td align='left' style='word-break:break-all;'>
                                                        &nbsp;" . stripslashes($arr["vfile_name"]). "</td>";
                                                        echo "<td style='word-break:break-all;'>".$arr["vlocation"]." </td>";
                                                        echo "<td >&nbsp;".$arr["vsite_name"]."</td>";

                                                        echo "<td >&nbsp;".$arr["ddate"]."</td>";

                                                        echo "<td >&nbsp;<a href=ftpstatus.php?fileid=".stripslashes($arr["nfile_id"])."&sitename=".$arr["vsite_name"]."&filename=".$arr["vfile_name"]."&location=".$arr["vlocation"]."&remotedir=".$arr["vremote_dir"]."  class=toplinks>Check Status</a></td>";
                                                        echo "<td >&nbsp;<a href=ftpdelete.php?fileid=".stripslashes($arr["nfile_id"])."&sitename=".$arr["vsite_name"]."&filename=".$arr["vfile_name"]."&location=".$arr["vlocation"]."&remotedir=".$arr["vremote_dir"]." class=toplinks>Delete</a></td>";
                                                        echo "</tr>";
                                                        $counter++;
                                                }
                                        ?>

                        <tr class=background>
                        <td colspan="7" align="center" height="30">
                        <?php echo($navigate[2]); ?>&nbsp;
                        </td>
                        </tr>
                        </table>

</form>

                </td>
                </tr>
                                <tr><td class=maintext>
                                       <br>
                                <div  class="common_box" align="center">
                                                                 <br>
                                <a href="upload.php" ><b>Upload a new file to the remote server</b></a>
                                                                 <br>
                                                                 <br>
                                </div>
								 <br>
								<div align="left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></div>

                                </td></tr>
                                <tr><td>&nbsp;</td></tr>
                <tr>
                <td>

                </td>
                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                </table>
</td>
</tr>
</table>

<?php
include "includes/userfooter.php";
?>