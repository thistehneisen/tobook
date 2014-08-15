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
$curTab = 'payments';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";
include_once "../includes/function.php";
include "includes/admin_functions.php";

$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch=($_GET["txtSearch"] != ""?trim($_GET["txtSearch"]):trim($_POST["txtSearch"]));
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
//$orderType      = ($_GET["orderType"] == "ASC"?"DESC":"ASC");
$orderType      = $_GET["orderType"];
$orderField     = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);
$fromDate   = ($_REQUEST["fromDate"])?date("Y-m-d",strtotime($_REQUEST["fromDate"])):'';
$toDate     = ($_REQUEST["toDate"])?date("Y-m-d",strtotime($_REQUEST["toDate"])):'';
if($begin == ""){ $begin = 0; $num   = 1; $numBegin=1; }


$sortUserArrow 			= '<img src="../images/bgar.gif">';
$sortSiteArrow 			= '<img src="../images/bgar.gif">';
$sortDateArrow 			= '<img src="../images/bgar.gif">';
$sortPaymentArrow 		= '<img src="../images/bgar.gif">';
$sortTrasactionArrow 	= '<img src="../images/bgar.gif">';
$sortAmountArrow 		= '<img src="../images/bgar.gif">';

switch($orderField){
    case 'vuser_name': $sortUserArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vsite_name': $sortSiteArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'ddate': $sortDateArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vpayment_type': $sortPaymentArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'vtxn_id': $sortTrasactionArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'namount': $sortAmountArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
}

?>
<link rel="stylesheet" href="../style/jquery-ui.css" />
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui.js"></script>
<script language="JavaScript" type="text/JavaScript">
$(document).ready(function () {
    $( "#fromDate" ).datepicker();
    $( "#toDate" ).datepicker();


    $('.startdate').click(function(){  $('#fromDate').trigger('focus'); });
    $('.enddate').click(function(){  $('#toDate').trigger('focus'); });
    
});
function clickSearch(){
    document.frmPayment.postback.value="S";
    document.frmPayment.action="payment.php";
    document.frmPayment.submit();
}
</script>
<div class="admin-pnl">
    <h2><?php echo PAYMENTS;?></h2>
    <div>
        <div style="font-size: 13px;"><?php echo $message;?></div>
    </div>

<div class="content-tab-pnl">
<form name="frmPayment" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">

<?php
$qryopt="";

 if($txtSearch != ""){
    if($cmbSearchType == "sitename"){
            $qryopt .= "  AND vsite_name like '" . addslashes($txtSearch) . "%'";
    }elseif($cmbSearchType == "date"){
            $qryopt .= "  AND date_format(p.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
    }
}
if($fromDate!='' || $toDate!='' ){
    if($fromDate!='')
    $qryopt .= "  AND Date_Format(p.ddate,'%Y-%m-%d') >= '" . addslashes($fromDate)."'";
    if($toDate!='')
    $qryopt .= " AND Date_Format(p.ddate,'%Y-%m-%d') <= '" . addslashes($toDate)."'";
    //$qryopt .= "  AND p.ddate >= '" . addslashes($fromDate). "' AND p.ddate <= '" . addslashes($toDate)."'";
}
//get the list of payments done
$sql="SELECT u.vuser_name,p.npayment_id,p.nsite_id,p.namount,p.vpayment_type,p.vtxn_id,Date_Format(p.ddate,'%m/%d/%Y') as ddate, s.vsite_name,p.nuser_id FROM tbl_payment p,tbl_site_mast s,tbl_user_mast u WHERE  p.nuser_id=u.nuser_id AND s.nsite_id=p.nsite_id" . $qryopt;

if($orderField!=""){
    if($orderField=='ddate') $orderSqlField = "Date_Format(p.ddate,'%Y-%m-%d')";
    else $orderSqlField = $orderField;
    $sql.=" order by $orderSqlField $orderType";
}else{
    $sql.=" order by p.ddate DESC";
} 
$session_back="payment.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch."&fromDate=" . $fromDate . "&toDate=" . $toDate;
$gbackurl = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));
//echopre($sql);
/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$pageCount = 10;
$navigate = pageBrowser($totalrows,5,$pageCount,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&fromDate=$fromDate&toDate=$toDate&orderField=$orderField&orderType=$orderType",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql);
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top">
    <div class="admin-listing">
     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr class=background>
                        <td width="100%">

                            <table  border="0">
                                <tr class="text">
                                     <td width="67%" valign="top" class="listing126">
                                    <div class="admin-search-pnl">
                                            <label><?php echo SEARCH;?></label>
                                            <select name="cmbSearchType" class="selectbox">
                                                    <option value="sitename"
                                                    <?php if ($cmbSearchType == "sitename" || $cmbSearchType == "") {
                                                            echo("selected");
                                                    }
                                                    ?>><?php echo SITE_NAME;?></option>
                                                    
                                            </select> &nbsp;
                                            <input type="text" name="txtSearch" size="20" maxlength="50" value="<?php echo($txtSearch); ?>"
                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }" class="textbox">&nbsp;
                                               <label><?php echo MAIL_FROM;?> </label>
                                               <input name="fromDate"  id="fromDate" type="text" value="<?php echo getDateFormat($fromDate);?>">
                                               <img src="../themes/Coastal-Green/calendar-icon.png" alt="" class="startdate">
                                               <label><?php echo MAIL_TO;?> </label>
                                               <input name="toDate" id="toDate" type="text" value="<?php echo getDateFormat($toDate);?>">
                                               <img src="../themes/Coastal-Green/calendar-icon.png" alt="" class="enddate">
                                               <a href="javascript:clickSearch();" class="btn05"><?php echo SEARCH;?></a>
                                               <a href="<?php echo "payment_excel.php?cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&fromDate=$fromDate&toDate=$toDate&orderField=$orderField&orderType=$orderType" ?>" class="grey-btn03 ryt"><?php echo EXPORT;?></a>
                                            <div class="clear"></div>
                                    </div>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>


                 <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="admin-table-list">
                    <tr class="blacksub">
                        <th width="5%" valign="top"  >#</th>
                        <th width="15%" valign="top"  ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vuser_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo USERNAME;?><?php echo $sortUserArrow;?></a></th>
                        <th width="30%"  valign="top" ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vsite_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SM_SITE_NAME;?><?php echo $sortSiteArrow;?></a></th>
                        <th width="10%" valign="top"  ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=ddate&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo DATE;?><?php echo $sortDateArrow;?></a></th>
                        <th width="16%" valign="top"  ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vpayment_type&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo PAYMENT_TYPE;?><?php echo $sortPaymentArrow;?></a></th>
                        <th width="15%" valign="top"  ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vtxn_id&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo TRANSACTION_ID;?><?php echo $sortTrasactionArrow;?></a></th>
                        <th width="10%"  valign="top" ><a href="<?php echo BASE_URL?>admin/payment.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=namount&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo AMOUNT;?><?php echo $sortAmountArrow;?></a></th>
                    </tr>

                    <?php
global $currencyArray;
   $currency = getSettingsValue('currency');
                    //loop and display the limited records being browsed
                    $counter=1;
                    if($totalrows > 0 ){
                    while ($arr = mysql_fetch_array($rs)) {
                        $page=(($num-1)*$pageCount)+$counter;
                        echo "<tr  class=background>
                                                        <td style='word-break:break-all;'
                                                         >&nbsp;".$page."</td>";
                        echo "<td align='left'  style='word-break:break-all;'>
                                                        &nbsp;<a href='userdetails.php?id=".$arr["nuser_id"]."'>" . htmlspecialchars(stripslashes($arr["vuser_name"])). "</a></td>";
                        echo "<td align='left' style='word-break:break-all;'>
                                                        &nbsp;" . stripslashes($arr["vsite_name"]). "</td>";
                        echo "<td style='word-break:break-all;'>".$arr["ddate"]." </td>";
                        echo "<td >&nbsp;" . stripslashes($paymnttype[$arr["vpayment_type"]]). "</td>";

                        echo "<td >&nbsp;" . stripslashes($arr["vtxn_id"]). "</td>";

                        echo "<td  >&nbsp;" .$currencyArray[$currency]['symbol']. stripslashes($arr["namount"]). "</td>";
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
                    <?php echo(SM_LISTING .$navigate[1].SM_OF .$totalrows.SM_RESULTS);?>
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
				
</td>
</tr>
</table>									

</form>
</div>
</div>


<?php
include "includes/adminfooter.php";
?>