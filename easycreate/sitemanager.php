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
$curTab = 'dashboard';

// include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php"; 
include "includes/sitefunctions.php";

unset($_SESSION['siteDetails']);

$siteLanguageOption = getSettingsValue("site_language_option");

$_SESSION['typePage']="";
$_SESSION['session_edittype']="";
$message_delete = "";

//If the user clicks on the delete link against a site
if ($_POST["postback"] == "D") {  
    $var_site_id   = $_POST["siteId"];
    $var_site_name = $_POST["siteName"];

    if ($var_site_id > 0) {
        $sql_delete = "Update tbl_site_mast set ndel_status='1' where nsite_id='" . addslashes($var_site_id) . "'";
        mysql_query($sql_delete) or die(mysql_error());
        $message_delete = "<font color='green'>'" .  $var_site_name."'" . SM_MESSAGE_DELETE. " .</font><br>&nbsp;";
    }else{
        $message_delete = "<font color='#FF0000'>".SM_MESSAGE_DELETE_NODATA.".</font><br>&nbsp;";
    }
    
} //END IF Delete

$size_per_user=0; 
$allowed_space=0;
if (isset($_POST['subbuild'])) {

    //$_POST['sitename'] = ereg_replace("[^+A-Za-z0-9]", "",$_POST['sitename']);
    //$_POST['sitename'] = preg_replace("/[^+A-Za-z0-9]/", "", $_POST['sitename']);
    $sitename = $_POST['sitename'];
    $errorFlag = 0;
    
    if(trim($sitename)==''){
        $message = SM_MESSAGE_VALIDATION_EMPTY_SITENAME;
    }
    else if (!isSitenameExist($sitename)) {
        $message = SM_MESSAGE_VALIDATION_SITENAME_EXISTS;
    } else if (!validateSizePerUser($_SESSION["session_userid"], $size_per_user, $allowed_space)) {
        $message = SM_MESSAGE_VALIDATION_MEMORY_EXCEEDS1 . human_read($size_per_user) . " <br>".SM_MESSAGE_VALIDATION_MEMORY_EXCEEDS2.": " . human_read($allowed_space) . ")<br>".SM_MESSAGE_VALIDATION_MEMORY_EXCEEDS3.".<br>&nbsp;<br>";
    } else {

        if($siteLanguageOption == "english"){
            if (!isValidsitename($sitename)) {
                $message = SM_MESSAGE_VALIDATION_SITENAME;
                $errorFlag = 1;
            }
        }

        if($errorFlag==0){
            if (get_magic_quotes_gpc() == 0) {
                $_POST['sitename'] = addslashes($_POST['sitename']);
            }

            $_SESSION['siteDetails']['siteInfo']['siteName'] = $_POST['sitename'];
            $_SESSION['session_currenttempsiteid'] = "";

            /*
            if ($_SESSION['session_templateselectedfromindex'] == "YES") {
                $locationurl = $_SESSION['session_locationurl'];
                $_SESSION['gtemplatebackurl'] = "sitemanager.php";
                header("location:$locationurl");
            } else {
                //header("location:buildtype.php");
                header("location:showcategories.php");
            }
            */
            header("location:showcategories.php");
            exit;
        }
    }
}
if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}
$_SESSION['session_edittype'] = "";
$_SESSION['session_published'] = "";
$_SESSION['session_paymentmode'] = ""; 

include "includes/userheader.php";
?>


<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmSites.postback.value="S";
        document.frmSites.action="sitemanager.php";
        document.frmSites.submit();
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

 function showpreview(id,status,type,template,user){

                   var leftPos = (screen.availWidth-500) / 2;
                   var topPos = (screen.availHeight-400) / 2 ;
                   winurl="showsitepreview.php?id=" + id +"&status="+status+"&type="+type+"&template="+template+"&user="+user;
                   insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
          //   winname="sitepreview";
            // winurl="showsitepreview.php?id=" + id +"&status="+status+"&type="+type+"&template="+template+"&user="+user;
                         //window.open(winurl,winname,'');

 }


</script>

<form name="frmSites" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">
<input type="hidden" name="siteId" id="siteId" value="">
<input type="hidden" name="siteName" id="siteName" value="">
<?php
// ========================================================
$qryopt1 = "";

$txtSearch = $_POST["txtSearch"];
$cmbSearchType = $_POST["cmbSearchType"];

if ($txtSearch != "") {
    if ($cmbSearchType == "sitename") {
        $qryopt1 .= "  AND vsite_name like '%" . addslashes($txtSearch) . "%'";
    }
    /*
    elseif ($cmbSearchType == "date") {
        $qryopt1 .= "  AND DATE_FORMAT(ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes(date('m/d/Y',  strtotime($txtSearch))) . "%'";
    } */
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
$pageCount = 10;
$navigate = pageBrowser($totalrows, 5, $pageCount, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_SITE_MANAGER =>'sitemanager.php');

 echo getBreadCrumb($linkArray); ?>

<h2><?php echo FOOTER_SITE_MANAGER; ?></h2>
<table width="100%">
    <tr>
        <td class=background>
            <?php require("./buildsite.php");
            ?>
        </td>
    </tr>
</table>
<strong class="or"><?php echo SM_OR; ?></strong>
<div class="border-pnl01">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" align="center" class="">
                <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <div class="create-top-pnl">
                                <div class="create-top-hd left"><h3><?php echo SM_CREATE_FROM; ?></h3></div>
                                <div class="create-top-srch right">
                                    <select name="cmbSearchType" class="selectbox">
                                        <option value="sitename" <?php if ($cmbSearchType == "sitename" || $cmbSearchType == "") { echo("selected"); } ?>><?php echo SM_SITE_NAME; ?></option>
                                        <!--option value="status"  <?php //if ($cmbSearchType == "status") { echo("selected"); } ?>><?php //echo SM_STATUS; ?></option-->
                                    </select>
                                                                            
                                    <input type="text" name="txtSearch" size="20" maxlength="50" value="<?php echo(htmlentities($txtSearch)); ?>"
                                    onKeyPress="if(window.event.keyCode == '13'){ return false; }" class="textbox" style="height:25px !important;">
                                                                            
                                    <a href="javascript:clickSearch();" class="search-grey">&nbsp;</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                                                            
                            <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="table-listing">
                                <div style="padding: 10px 0 0px 0; font-size: 16px;"><?php echo($message_delete); ?></div>
                                <tr>
                                    <th width="31%" valign="top"><?php echo SM_SITE_NAME; ?></th>
                                    <th width="15%"><?php echo SM_DATE_CREATED; ?></th>
                                    <th width="15%"><?php echo SM_STATUS; ?></th>
                                    <th width="15%" colspan="2"><?php echo SM_OPERATIONS; ?></th>
                                    <th width="8%"><?php echo HOME_PREVIEW; ?></th>
                                </tr>
                                <?php
                                // loop and display the limited records being browsed
                                if(mysql_num_rows($rs)>0){
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) {
                                    echo "<tr class='text'> <td align='left' style='word-break:break-all;'><a href=\"javascript:clickEdit(" . $arr["nsite_id"] . ");\">" . stripslashes($arr["vsite_name"]) . "</a></td>";
                                    echo "<td  align='left' style='word-break:break-all;'> &nbsp;" . stripslashes($arr["ddate"]) . "</td>";
                                    echo "<td  style='word-break:break-all;'>" . $arr["status"] . " </td>";
                                    echo "<td align='left' >&nbsp;<a href=\"javascript:clickEdit(" . $arr["nsite_id"] . ");\">".SM_EDIT."</a></td>";
                                    echo "<td  >&nbsp;<a href=\"#\" onClick=\"javascript:clickDelete(" . $arr["nsite_id"] .",'". $arr["vsite_name"]."');\" style=\"text-decoration:none;\">".SM_DELETE."</a></td>";
                                    //echo "<td >&nbsp;<a href=javascript:showpreview('" . $arr["nsite_id"] . "','" . (($arr["status"] == "Completed") ? 1 : 0) . "','" . $arr["vtype"] . "','" . $arr["ntemplate_id"] . "','" . $_SESSION["session_userid"] . "')>".HOME_PREVIEW."</a></td>";
                                    echo "<td >&nbsp;<a href='workarea/sites/$arr[nsite_id]/index.html' target='_blank'>".HOME_PREVIEW."</a></td>";
                                    echo "</tr>";
                                    $counter++;
                                }}else{
                                ?>
                                <tr><td><?php echo SORRY_NO_RECORDS;?></td></tr>
                                <?php } ?>
                                <tr><td>&nbsp;</td></tr>
                                <?php if($totalrows > $pageCount){ ?>
                                <tr>
                                    <td width="30%" valign="bottom"	class="category bordr-top"><?php echo SM_LISTING." ". $navigate[1]." ". SM_OF." ". $totalrows. " " . SM_RESULTS; ?></td>
                                    <td colspan="5" align="right" class="bordr-top">
                                        <div class="pagination ryt">
                                            <?php echo($navigate[2]);
                                                ?>&nbsp;
                                        </div>
                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="clear"></div>
</div>
<?php

include "includes/userfooter.php";

?>
