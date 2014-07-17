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
// +----------------------------------------------------------------------+

$curTab = 'users';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";
include "includes/admin_functions.php";
$msg        = $_GET["msg"];
if($_GET['type']=='login'){
    $_SESSION["session_userid"] =  $_GET["id"];
    $_SESSION["session_loginname"] =  $_GET["name"];
    $_SESSION["session_email"] =  $_GET["email"];
    $root_path=getSettingsValue('rootserver');
    $location= BASE_URL."sitemanager.php";
    
    ?>
<script>window.open('<?php echo $location?>')</script>
<?php
}
if($_GET["delact"]=="delete"){

    //change status of user to inactive
    $sql="Update tbl_user_mast set vdel_status='1' where nuser_id='".$_GET["delid"]."'";
    mysql_query($sql,$con);

    //remove site entries
    $sql="Select nsite_id from tbl_site_mast where nuser_id='".$_GET["delid"]."'";
    $result=mysql_query($sql,$con);
    
    while($row=mysql_fetch_array($result)){
        if(is_dir(("sites/".$row["nsite_id"]))){
            remove_dir("sites/".$row["nsite_id"]);
        }
         if(is_dir(("workarea/sites/".$row["nsite_id"]))){
            remove_dir("workarea/sites/".$row["nsite_id"]);
         }
    }

    //remove temperory site entries
    $sql="Select ntempsite_id from tbl_tempsite_mast where nuser_id='".$_GET["delid"]."'";
    $result=mysql_query($sql,$con);

    while($row=mysql_fetch_array($result)){
        if(is_dir(("workarea/".$row["nsite_id"]))){
            remove_dir("workarea/".$row["nsite_id"]);
        }
           
    }

    //remove files from user gallery
    if(is_dir(("usergallery/".$_GET["delid"]))){
        remove_dir("usergallery/".$_GET["delid"]);
    }
    $msg="<font color='green'>User deleted successfully</font>";
    //echo $location=$_SESSION["gbackurl"].'&msg='.$msg;
    header("location:".$location);
    //exit;
}
?>
<link rel="stylesheet" href="../style/jquery-ui.css" />
<script src="../js/jquery.js"></script>
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui.js"></script>
<script language="JavaScript" type="text/JavaScript">
    $(document).ready(function () {
        $( "#fromDate" ).datepicker();
        $( "#toDate" ).datepicker();
    });
function clickSearch()
{
    document.frmUser.postback.value="S";
    document.frmUser.action="usermanager.php";
    document.frmUser.submit();
}

function deleteUser(id){
ans=confirm("<?php echo CONF_USER_DELETE;?>")

        if(ans==true){

                document.frmUser.delid.value=id;
                document.frmUser.delact.value="delete";
        document.frmUser.action="usermanager.php";
                document.frmUser.submit();

        }
}
</script>

<div class="admin-pnl">
    <h2><?php echo USERS;?></h2>
    <div>
        <div style="color:red; font-size: 13px;"><?php echo $message;?></div>
    </div>

<div class="content-tab-pnl">
<form name="frmUser" >
    <input name="postback" type="hidden" id="postback">
    <input name="delact" type="hidden" id="delact">
    <input name="delid" type="hidden" id="delid">

<?php
//$msg        = $_GET["msg"];
$begin      =($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num        =($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin   =($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch  =($_GET["txtSearch"] != ""?$_GET["txtSearch"]:$_POST["txtSearch"]);
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
//$orderType  = ($_GET["orderType"] == "ASC"?"DESC":"ASC");
$orderType      = $_GET["orderType"];
$orderField = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);
$fromDate   = ($_REQUEST["fromDate"])?date("Y-m-d",strtotime($_REQUEST["fromDate"])):'';
$toDate     = ($_REQUEST["toDate"])?date("Y-m-d",strtotime($_REQUEST["toDate"])):'';
if($begin == ""){ $begin = 0; $num   = 1; $numBegin=1; }


$sortLoginArrow = '<img src="../images/bgar.gif">';
$sortNameArrow 	= '<img src="../images/bgar.gif">';
$sortEmailArrow = '<img src="../images/bgar.gif">';
$sortDateArrow 	= '<img src="../images/bgar.gif">';

switch($orderField){
    case 'vuser_login': $sortLoginArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vuser_name': $sortNameArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vuser_email': $sortEmailArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'date': $sortDateArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
}


$qryopt="";

 if($txtSearch != ""){
        if($cmbSearchType == "name"){
                $qryopt .= "  AND vuser_name like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "email"){
                $qryopt .= "  AND vuser_email like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "date"){
                $qryopt .= "  AND date_format(duser_join,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        }
}
/*
if($fromDate!='' && $toDate!='' ){
    $qryopt .= "  AND duser_join >= '" . addslashes($fromDate). "' AND duser_join <= '" . addslashes($toDate)."'";
} */

if($fromDate!='' || $toDate!='' ){
    if($fromDate!='')
    $qryopt .= " AND duser_join >= '" . addslashes($fromDate)."'";
    if($toDate!='')
    $qryopt .= " AND duser_join <= '" . addslashes($toDate)."'";
}

$sql="select nuser_id, vuser_login,vuser_name,vuser_email,vuser_phone,Date_Format(duser_join,'%m/%d/%Y') as date from tbl_user_mast where vdel_status='0' " . $qryopt;
if($orderField!=""){
    if($orderField=='date') $orderSqlField = "Date_Format(duser_join,'%Y-%m-%d')";
    else $orderSqlField = $orderField;
    $sql.=" order by $orderSqlField $orderType";
}else{
    $sql.=" order by duser_join DESC";
}

//echopre($sql);
$session_back="usermanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch ."&fromDate=" . $fromDate . "&toDate=" . $toDate;
$_SESSION["gbackurl"] = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$pageCount = 10;
$navigate = pageBrowser($totalrows,5,$pageCount,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&fromDate=$fromDate&toDate=$toDate&orderField=$orderField&orderType=$orderType",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql) or die(mysql_error());
$currnt = isset($_GET[num]) ? $_GET[num] : 1;
$rowCount = ($currnt-1)*$pageCount;
//$page=(($num-1)*$pageCount)+$counter;
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top">
            <div align="right"><a href="adduser.php" class="btn05"><?php echo ADD_USER;?></a></div>
            <div class="admin-listing">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <!--td class="listingmain"-->
                        <td>
                            <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="100%">
                                    <table  border="0" width="100%">
                                            <tr>
                                                    <td width="67%" valign="top" class="listing126">
                                                        <div><?php if($msg!=''){echo $msg;}?></div>
                                                    <div class="admin-search-pnl">
                                                            <label><?php echo SEARCH;?></label>
                                                            <select name="cmbSearchType" class="selectbox">
                                                                    <option value="name"
                                                                    <?php if ($cmbSearchType == "name" || $cmbSearchType == "") {
                                                                            echo("selected");
                                                                    }
                                                                    ?>><?php echo SIGNUP_NAME;?></option>
                                                                    <option value="email"
                                                                                    <?php
                                                                                    if ($cmbSearchType == "email" || $cmbSearchType == "") {
                                                                                            echo("selected");
                                                                                    }
                                                                                    ?>><?php echo SIGNUP_EMAIL;?></option>
                                                                    <!--option value="date"
                                                                    <?php /*
                                                                    if ($cmbSearchType == "date" || $cmbSearchType == "") {
                                                                            echo("selected");
                                                                    } */
                                                                    ?>>Date</option-->
                                                            </select> &nbsp;
                                                            <input type="text" name="txtSearch" size="20" maxlength="50"
                                                               value="<?php echo($txtSearch); ?>"
                                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                                               class="textbox">&nbsp;
                                                               <label><?php echo MAIL_FROM;?> </label>
                                                               <input name="fromDate"  id="fromDate" type="text" value="<?php echo getDateFormat($fromDate);?>">
                                                               <img src="../themes/Coastal-Green/calendar-icon.png" alt="">
                                                               <label><?php echo MAIL_TO;?></label>
                                                               <input name="toDate" id="toDate" type="text" value="<?php echo getDateFormat($toDate);?>">
                                                               <img src="../themes/Coastal-Green/calendar-icon.png" alt="">
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
                                
                                
                            <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="admin-table-list">
                                <tr>
                                    <th width="1%" valign="top">#</th>
                                    <th width="15%" valign="top"><a href="<?php echo BASE_URL?>admin/usermanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&fromDate=<?php echo $fromDate?>&toDate=<?php echo $toDate?>&orderField=vuser_login&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo USERNAME;?><?php echo ($sortLoginArrow)?'':'' ?><?php echo $sortLoginArrow; ?></a></th>
                                    <th width="15%" ><a href="<?php echo BASE_URL?>admin/usermanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&fromDate=<?php echo $fromDate?>&toDate=<?php echo $toDate?>&orderField=vuser_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC";?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SIGNUP_NAME;?><?php echo $sortNameArrow; ?></a></th>
                                    <th width="20%" ><a href="<?php echo BASE_URL?>admin/usermanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&fromDate=<?php echo $fromDate?>&toDate=<?php echo $toDate?>&orderField=vuser_email&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC";?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SIGNUP_EMAIL;?><?php echo $sortEmailArrow; ?></a></th>
                                    <th width="15%" ><?php echo SIGNUP_PHONE;?></th>
                                    <th width="12%" ><a href="<?php echo BASE_URL?>admin/usermanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&fromDate=<?php echo $fromDate?>&toDate=<?php echo $toDate?>&orderField=date&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC";?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo JOIN_DATE;?><?php echo $sortDateArrow;?></a></th>
                                    <th width="5%" ><?php echo EDIT;?></th>
                                    <th width="5%" ><?php echo DELETE;?></th>
                                    <th width="18%" ><?php echo CREATE_SITE;?></th>
                                </tr>
                                    
                                <?php
                                //loop and display the limited records being browsed
                                $counter = 1;
                                if($totalrows > 0 ){
                                    while ($arr = mysql_fetch_array($rs)) {
                                        $rowCount++;
                                        $userlink = "sitemanager.php?user_id=".$arr["nuser_id"]."&begin=0&num=1&numBegin=1&cmbSearchType=username&txtSearch=" . $arr["vuser_login"];
                                        echo "<tr   class=background>
                                            <td width=\"4%\">$rowCount</td>
                                            <td style='word-break:break-all;'
                                             align='left' >&nbsp;<a href='$userlink' title='View sites created by " . $arr["vuser_login"] . "'>" . stripslashes($arr["vuser_login"]) . "</a></td>";

                                        echo "<td  align='left'  style='word-break:break-all;'>
                                                            &nbsp;" . stripslashes($arr["vuser_name"]) . "</td>";
                                        echo "<td align='left' style='word-break:break-all;'>" . $arr["vuser_email"] . " </td>";
                                        echo "<td align='left'>&nbsp;" . stripslashes($arr["vuser_phone"]) . "</td>";

                                        echo "<td align='center'>" . stripslashes($arr["date"]) . "</td>";

                                        echo "<td >&nbsp;<a href='edituser.php?id=" . stripslashes($arr["nuser_id"]) . "' >".EDIT."</a></td>";
                                        echo "<td >&nbsp;<a href=javascript:deleteUser('" . stripslashes($arr["nuser_id"]) . "') >".DELETE."</a></td>";
                                        echo "<td><a href='usermanager.php?type=login&id=".$arr["nuser_id"]."&name=".$arr["vuser_login"]."&email=".$arr["vuser_email"]."' class='link02'>".LOG_IN."</a></td>";
                                        echo "</tr>";
                                        $counter++;
                                    }
                                }else{ ?>
                                    <tr><td colspan="8" style="text-align: center;"><b><?php echo NO_DATA_FOUND;?>!</b></td></tr>
                                <?php }
                                ?>

                            </table>
                            <?php if($totalrows >$pageCount){ ?>
                                <div class="admin-table-btm">
                                    <div class="total-list lft">
                                    <?php echo(SM_LISTING.$navigate[1].SM_OF. $totalrows.SM_RESULTS);?>
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
                <div class="clear"></div>
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