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
$curTab = 'profile';
//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
include_once "includes/function.php";
//get get/post parameters for paging function
$begin = ($_GET["begin"] != "" ? $_GET["begin"] : $_POST["begin"]);
$num = ($_GET["num"] != "" ? $_GET["num"] : $_POST["num"]);
$numBegin = ($_GET["numBegin"] != "" ? $_GET["numBegin"] : $_POST["numBegin"]);
$txtSearch = ($_GET["txtSearch"] != "" ? trim($_GET["txtSearch"]) : trim($_POST["txtSearch"]));
$cmbSearchType = ($_GET["cmbSearchType"] != "" ? $_GET["cmbSearchType"] : $_POST["cmbSearchType"]);
$title		= isset($_REQUEST['title'])	?	trim($_REQUEST['title'])	:	"";
$order		= isset($_REQUEST['order'])	?	trim($_REQUEST['order'])	:	"";

$image='<img src="images/bgar.gif"></img>';
if($order=='DESC') {

    $image='<img src="images/descar.gif"></img>';
}

else if($order=='ASC') {

    $image='<img src="images/ascrar.gif" ></img>';

}


?>
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<link rel="stylesheet" href="style/jquery-ui.css" />
<script src="js/jquery-1.8.3.js"></script>
<script src="js/jquery-ui.js"></script>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function () {

    var searchType = '<?php echo $_REQUEST['cmbSearchType'] ?>';
    if(searchType == 'date'){
        $("#txtSearch").datepicker();
    }else{
        $("#txtSearch").datepicker("destroy");
    }

});

    function clickSearch()
    {
        document.frmPayment.postback.value="S";
        document.frmPayment.action="viewpayment.php";
        document.frmPayment.submit();
    }
    function resetValues(){
        $("#txtSearch").val(" ");
    }

    function toggleSerachType(){
        var searchType = $("#cmbSearchType").val(); 
        if(searchType == 'date'){
            $("#txtSearch").datepicker();
        }else{
            $("#txtSearch").datepicker("destroy");
        }
    }
</script>

<form name="frmPayment" method="post" action="">
    <input name="postback" type="hidden" id="postback">
    <input name="id" type="hidden" id="id">

    <?php
//========================================================
    $qryopt = "";

//$txtSearch=$_POST["txtSearch"];
//$cmbSearchType=$_POST["cmbSearchType"];
    if ($txtSearch != "") {

        if ($cmbSearchType == "site name") {
            $qryopt .= "  AND vsite_name like '" . addslashes($txtSearch) . "%'";
        }elseif ($cmbSearchType == "date") {
            $qryopt .= "  AND date_format(p.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        }elseif ($cmbSearchType == "viewall") {
            $qryopt .= " ";
            $txtSearch = "";
        }
    }

//get the data of all payments made
    $sql = "SELECT p.npayment_id,p.nsite_id,p.namount,p.vpayment_type,p.vtxn_id,Date_Format(p.ddate,'%m/%d/%Y') as ddate,s.vsite_name FROM tbl_payment p,tbl_site_mast s WHERE p.nuser_id='" . $_SESSION["session_userid"] . "'and s.nsite_id=p.nsite_id" . $qryopt ;
    if($title=='') {
        $sql    .="  order by p.ddate DESC";
    }
    else {
        $sql    .=" ORDER BY ".$title."  ".$order;
    }
    $session_back = "viewpayment.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
    $gbackurl = $session_back;
//get the total amount of rows returned
    $totalrows = mysql_num_rows(mysql_query($sql));

    /*
  Call the function:

  I've used the global $_GET array as an example for people
  running php with register_globals turned 'off' :)
    */
    $navigate = pageBrowser($totalrows, 10, 10, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&title=$title&order=$order", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);

//execute the new query with the appended SQL bit returned by the function
    $sql = $sql . $navigate[0];
    $rs = mysql_query($sql);
    ?>

    <?php $linkArray = array(
            TOP_LINKS_DASHBOARD=>'usermain.php',
            TOP_LINKS_MY_ACCOUNT =>'profilemanager.php',
            PROFILE_MANAGER_PAYMENT =>'viewpayment.php');
    echo getBreadCrumb($linkArray);?>

    <h2><?php echo PROFILE_MANAGER_PAYMENT; ?></h2>

    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="text">
                                <tr>
                                    <td width="100%">
                                        <table  border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="100%" valign="top" class="listing126" colspan="3">
                                                    <div class="admin-search-pnl">
                                                        <label>Search </label>

                                                        <select name="cmbSearchType" id="cmbSearchType" class="selectbox select10" onchange="return toggleSerachType()">
                                                            <option value="site name"
                                                            <?php if ($cmbSearchType == "site name" || $cmbSearchType == "") {
                                                                echo("selected");
                                                            }
                                                            ?>>Site Name</option>
                                                            <option value="date"  <?php
                                                            if ($cmbSearchType == "date") {
                                                                echo("selected");
                                                            }
                                                            ?>>Date(mm/dd/yyyy)</option>
                                                            <option value="viewall"  <?php
                                                    if($cmbSearchType == "viewall" ) {
                                                        echo("selected");
                                                            } ?>>View All</option>
                                                        </select> &nbsp;
                                                        <input type="text" id="txtSearch" name="txtSearch" size="20" maxlength="50"
                                                               value="<?php echo(htmlentities($txtSearch)); ?>"
                                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                                               class="textbox txt10">&nbsp;
                                                        <a href="javascript:clickSearch();" class="btn05">Search</a>
                                                        <input class="grey-btn04"  type="button" name="reset" id="reset" value="Reset" onclick="return resetValues();" />
                                                        <div class="clear"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>


                            <?php
                            $currency = getSettingsValue('currency');
                            $curSymbol = $currencyArray[$currency]['symbol'];
                            ?>

                            <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="admin-table-list">
                                <tr>
                                    <th width="2%" valign="top">#</th>
                                    <th width="30%"><a href="viewpayment.php?title=vsite_name&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Site Name</a></th>
                                    <th width="15%"><a href="viewpayment.php?title=ddate&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Date</th>
                                    <th width="15%"><a href="viewpayment.php?title=vpayment_type&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Payment Type</th>
                                    <th width="12%"><a href="viewpayment.php?title=vtxn_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Transaction ID</th>
                                    <th width="12%"><a href="viewpayment.php?title=namount&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Amount(<?php echo $curSymbol;?>)</th>
                                </tr>

                                <?php
                                //loop and display the limited records being browsed
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) {
                                    echo "<tr   class=background>
                                                        <td style=''
                                                       >&nbsp;" . $counter . "</td>";
                                    echo "<td  style=''>
                                                        &nbsp;" . stripslashes($arr["vsite_name"]) . "</td>";
                                    echo "<td   style='word-break:break-all;'>" . $arr["ddate"] . " </td>";
                                    echo "<td >&nbsp;" . stripslashes($arr["vpayment_type"]) . "</td>";

                                    echo "<td  >&nbsp;" . stripslashes($arr["vtxn_id"]) . "</td>";

                                    echo "<td>&nbsp;" . stripslashes($arr["namount"]) . "</td>";
                                    echo "</tr>";
                                    $counter++;
                                }
                                ?>
                            </table>
                            <div class="admin-table-btm">
                                <div class="total-list lft">
                                <?php if($totalrows > 0){ echo("Listing $navigate[1] of $totalrows results."); } ?> &nbsp; &nbsp;
                                <?php echo($navigate[2]);
                                    ?>&nbsp;</div>
                                <div class="list-pagin ryt"><a href="profilemanager.php" class="grey-btn04" >Back</a></div>
                            </div>
                            </form>
                        </td>
                    </tr>
                </table>
                <div align="left"></div>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
    include "includes/userfooter.php";
    ?>