<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+

$curTab = 'template_manager';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";
include "includes/admin_functions.php";

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function isCategoryDeletable($catid){
        global $con;
        $sql = "SELECT count(ncat_id) as cnt FROM tbl_template_mast WHERE ncat_id = '".addslashes($catid)."' ";
        $res = mysql_query($sql,$con);

        if(mysql_num_rows($res) > 0){
                $row = mysql_fetch_array($res);
                $count = $row["cnt"];
                if($count > 0){
                        return false;
                }else{
                        return true;
                }
        }else{
                return true;
        }
}

if($_GET["delact"]=="delete"){
        if(isCategoryDeletable($_GET["delid"])){
       
                $sql="DELETE FROM tbl_template_category WHERE ncat_id='".$_GET["delid"]."'";
                mysql_query($sql,$con);
                //header("location:".$_SESSION["tempcat_backurl"]);
                $msg="<font color='green'>Template category deleted</font>";
                header("location:tempcategorymanager.php");
               
        }else{
                $message = "This category cannot be deleted since it is in use! ";
        }

}

?>
<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmCategories.postback.value="S";
        document.frmCategories.action="tempcategorymanager.php";
        document.frmCategories.submit();
}

function deleteCategory(id){
        if(confirm("<?php echo CONF_DELETE_CAT;?>")){
                document.frmCategories.delid.value=id;
                document.frmCategories.delact.value="delete";
                document.frmCategories.action="tempcategorymanager.php";
                document.frmCategories.submit();
        }
}


</script>
<?php
$linkArray = array( TEMP_MANAGER     =>'admin/templatemanager.php',
                    TEMP_CATEGORIES  =>'admin/tempcategorymanager.php');
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray); ?>
    <h2><?php echo TEMP_CATEGORIES;?></h2>

<div class="content-tab-pnl">
<form name="frmCategories" >
<input name="postback" type="hidden" id="postback">
<input name="delact" type="hidden" id="delact">
<input name="delid" type="hidden" id="delid">

<?php

$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch=($_GET["txtSearch"] != ""?trim($_GET["txtSearch"]):trim($_POST["txtSearch"]));
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
$orderType      = $_GET["orderType"];
$orderField     = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);

if($begin == ""){ $begin=0; $num=1; $numBegin=1; }


$sortNameArrow 		= '<img src="../images/bgar.gif">';
$sortDescArrow 		= '<img src="../images/bgar.gif">';

switch($orderField){
    case 'vcat_name': $sortNameArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vcat_desc': $sortDescArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
}

$qryopt="";

if($txtSearch != ""){
    if($cmbSearchType == "name"){
            $qryopt .= "  WHERE vcat_name like '%" . urldecode(addslashes($txtSearch))  . "%'";
    }elseif($cmbSearchType == "description"){
            $qryopt .= "  WHERE vcat_desc   like '%" . urldecode(addslashes($txtSearch))  . "%'";
    }
}

$sql="select * from tbl_template_category " . $qryopt;

if($orderField!=""){
    $sql.=" order by $orderField $orderType";
}else{
    $sql.=" order by vcat_name";
}

//echo $sql;


$session_back="tempcategorymanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . urlencode($txtSearch);
$_SESSION["tempcat_backurl"] = $session_back;

//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$txtSearch1= urlencode($txtSearch);
$pageCount = 10;
$navigate = pageBrowser($totalrows,5,$pageCount,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch1&orderField=$orderField&orderType=$orderType",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql) or die(mysql_error());
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div class="admin-listing">
                <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="100%">
                                        <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td colspan="4" align="center" width="100%"><?php echo($msg); ?></td>
                                            </tr>
                                            <tr>
                                                <td  class="listing126">
                                                    <div class="admin-search-pnl">
                                                        <label><?php echo SEARCH;?></label>
                                                        <select name="cmbSearchType" class="selectbox" style="width: 200px;">
                                                            <option value="name"
                                                            <?php  if($cmbSearchType == "name" || $cmbSearchType == "") { echo("selected"); } ?>><?php echo SIGNUP_NAME;?>
                                                            </option>
                                                            <option value="description"
                                                            <?php if($cmbSearchType == "description") { echo("selected"); } ?>><?php echo DESCRIPTION;?></option>
                                                        </select> &nbsp;
                                                        <input type="text" name="txtSearch" size="20" maxlength="50" style="width: 275px;"
                                                               value="<?php echo($txtSearch); ?>"
                                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                                               class="textbox">&nbsp;
                                                        <a href="javascript:clickSearch();" class="btn05"><?php echo SEARCH;?></a>
                                                        <a href="edittemplatecategory.php" class="grey-btn03 ryt"><?php echo ADD_NEW_CATGORY;?></a>
                                                        <div class="clear"></div>
                                                    </div>
                                    
                                </td></tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>

                <table width="100%"  border="0" cellpadding="5" cellspacing="1" class="admin-table-list">
                    <tr class="blacksub">
                        <td width="5%" valign="top" >#</td>
                        <td width="20%" valign="top" ><a href="<?php echo BASE_URL?>admin/tempcategorymanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vcat_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SIGNUP_NAME;?><?php echo $sortNameArrow;?></a></td>
                        <td width="45%" valign="top"><a href="<?php echo BASE_URL?>admin/tempcategorymanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vcat_desc&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo DESCRIPTION;?><?php echo $sortDescArrow;?></a></td>
                        <td width="10%"><?php echo THUMBNAIL;?></td>
                        <td width="10%" ><?php echo EDIT;?></td>
                        <td width="10%"><?php echo DELETE;?></td>
                    </tr>

                    <?php

                    //loop and display the limited records being browsed
                    $counter=1;
				if(mysql_num_rows($rs) > 0) {
                    while ($arr = mysql_fetch_array($rs)) {
                         $page=(($num-1)*$pageCount)+$counter;
                        $thumpnaail="../".$arr["vcat_thumpnail"];
                        $desc =($arr["vcat_desc"])?$arr["vcat_desc"]:"&nbsp;";
                        echo "<tr   class=background  class='text'>
                                                        <td style='word-break:break-all;'
                                                        align='left'>&nbsp;".stripslashes($page)."</td>";
                        echo "<td  align='left' style='word-break:break-all;'>
                                                        &nbsp;" .stripslashes($arr["vcat_name"]) . "</td>";
                        echo "<td  align='left' style='word-break:break-all;'>".$desc." </td>";
                        echo "<td  align='left' style='word-break:break-all;'><img border=0 src='".$thumpnaail."'> </td>";
                        echo "<td >&nbsp;<a href='edittemplatecategory.php?catid=". stripslashes($arr["ncat_id"]) ."' class=subtext>".EDIT."</a></td>";
                        echo "<td >&nbsp;<a href=javascript:deleteCategory('". stripslashes($arr["ncat_id"]) ."') class=subtext>".DELETE."</a></td>";
                        echo "</tr>";
                        $counter++;
                    }
				}
				else {
				echo '<tr><td colspan="6">'.NO_RESULT_FOUND.'</td></tr>';
				}
                    ?>
                   
                </table>    
                    <?php if($totalrows >$pageCount){ ?>
                    <div class="admin-table-btm">
                    <div class="total-list lft">
                    <?php echo(SM_LISTING.$navigate[1] .SM_OF. $totalrows.SM_RESULTS);?>
                    </div>
                    <div class="list-pagin ryt">
                    <?php echo($navigate[2]); ?>
                    </div>
                    <div class="clear"></div>
                    </div>
                    <?php } ?>

                    </td>
                </tr>
                </table>
    </div>

</td>
</tr>
</table>


</form>

</div>
</div>

<?php
include "includes/adminfooter.php";
?>