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

$curTab = 'contents';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";
include_once "../includes/function.php";

$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
//$orderType = ($_GET["orderType"] == "ASC"?"DESC":"ASC");
$orderType      = $_GET["orderType"];
$orderField = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);
$txtSearch  =($_GET["txtSearch"] != ""?$_GET["txtSearch"]:$_POST["txtSearch"]);
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
if($begin == ""){
    $begin=0;
    $num=1;
    $numBegin=1;
}
?>
<script>
function clickSearch()
{
    document.frmPayment.postback.value="S";
    document.frmPayment.action="cmslisting.php";
    document.frmPayment.submit();
}
</script>
<div class="admin-pnl">
    <h2><?php echo CONTENTS;?></h2>
<div class="content-tab-pnl">
<form name="frmPayment" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">

<?php

//get the list of payments done
$sql="SELECT * FROM tbl_cms";
if($txtSearch != ""){
        if($cmbSearchType == "setionname"){
                $sql.="  WHERE section_name like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "sectiontitle"){
                $sql.= "  WHERE  section_title like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "viewall"){
                $sql.= "  WHERE 1";
                $txtSearch = "";
        }
}

if($orderField!=""){
    $sql.=" order by $orderField $orderType";
}else{
    $sql.=" order by section_title  ASC";
}

$session_back="cmslisting.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin;
$gbackurl = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));
/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$pageCount = 10;
$navigate = pageBrowser($totalrows,5,$pageCount,"",$_GET['numBegin'],$_GET['start'],$_GET['begin'],$_GET['num']);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql);
?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td >
            
            <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%">
                        <table  border="0" width="100%">
                            <tr>
                                <td width="67%" valign="top" class="listing126">
                                    <div><?php if ($msg != '') {
                                            echo $msg;
                                        } ?></div>
                                    <div class="admin-search-pnl">
                                        <label><?php echo SEARCH;?></label>
                                        <select name="cmbSearchType" class="selectbox" style="width:120px;">
                                            <option value="setionname"
                                            <?php
                                            if ($cmbSearchType == "setionname" || $cmbSearchType == "") {
                                                echo("selected");
                                            }
                                            ?>><?php echo SECTION_NAME;?></option>
                                            <option value="sectiontitle"
                                            <?php
                                            if ($cmbSearchType == "sectiontitle" || $cmbSearchType == "") {
                                                echo("selected");
                                            }
                                            ?>><?php echo SECTION_TITLE;?></option>
                                            <option value="viewall"
                                            <?php
                                            if ($cmbSearchType == "viewall" || $cmbSearchType == "") {
                                                echo("selected");
                                            }
                                            ?>><?php echo VIEW_ALL;?></option>
                                            <!--option value="date"
                                            <?php /*
                                              if ($cmbSearchType == "date" || $cmbSearchType == "") {
                                              echo("selected");
                                              } */
                                            ?>>Date</option-->
                                        </select> &nbsp;
                                        <input type="text" name="txtSearch" size="20" maxlength="50" style="width:300px;"
                                               value="<?php echo($txtSearch); ?>"
                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                               class="textbox">&nbsp;
                                        
                                        <a href="javascript:clickSearch();" class="btn05"><?php echo SEARCH;?></a>
                                        <!--<a href="<?php echo "user_excel.php?cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&fromDate=$fromDate&toDate=$toDate&orderField=$orderField&orderType=$orderType" ?>" class="grey-btn03 ryt">Export</a>-->
                                        <div class="clear"></div>
                                    </div>
                                </td>
                                <!--td width="5%" valign="middle" class="listing126"><a href="javascript:clickSearch();">
                                        <img src='.././images/go.gif'  width="20" height="20" border='0'></a>
                                </td-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
                
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <?php if ($_SESSION['successMsg']) { ?>
                    <tr>
                        <td> <div class="successmessage"><?php echo $_SESSION['successMsg'];
                    unset($_SESSION['successMsg']); ?></div></td>
                    </tr>
                    <?php } ?>
                <tr>
                    <td  class="listing126">
                        <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="admin-table-list">
                            <tr>
                                <th width="35%"><a href="<?php echo BASE_URL ?>admin/cmslisting.php?orderField=section_name&orderType=<?php echo ($_GET["orderType"] == "ASC") ? "DESC" : "ASC" ?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SECTION_NAME;?></a></th>
                                <th width="45%"><a href="<?php echo BASE_URL ?>admin/cmslisting.php?orderField=section_title&orderType=<?php echo ($_GET["orderType"] == "ASC") ? "DESC" : "ASC" ?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SECTION_TITLE?></a></th>
                                <th width="20%"><?php echo OPTIONS;?></th>
                            </tr>
                            <?php
                            if(mysql_num_rows($rs) > 0 ){
                            while ($arr = mysql_fetch_array($rs)) {
                                ?>
                                <tr class=background>
                                    <td><?php echo $arr['section_name']; ?></td>
                                    <td><?php echo $arr['section_title']; ?></td>
                                    <td><a href="contentmanagement.php?mode=<?php echo $arr['section_name']; ?>"><?php echo EDIT;?></a></td>
                                </tr>
                                    <?php if ($totalrows > $pageCount) { ?>
                                    <tr>
                                        <td width="30%" valign="bottom" class="category bordr-top"><?php echo(SM_LISTING.$navigate[1].SM_OF.$totalrows.SM_RESULTS); ?></td>
                                        <td colspan="8" align="right" class="bordr-top">
                                    <?php echo($navigate[2]); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } }else{  ?>
                                    <tr class=background>
                                        <td colspan="3"><?php echo NO_RESULT_FOUND;?>!</td>
                                    
                                </tr>

                                    <?php } ?>
                        </table>
                    </td>
                </tr>
            </table>
                
        </td>
    </tr>
</table>									
    
</form>
</div>
</div>

<?php
include "includes/adminfooter.php";
?>