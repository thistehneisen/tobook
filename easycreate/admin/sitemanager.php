<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish <girish@armia.com>        		              |
// +----------------------------------------------------------------------+
//Admin's Sitemanager
//List all the sites created, facility to search against sitename,user name, date of creation is given
//Paging is given, with 10 sites per page. 
//Four tables dummy,tbl_tempsite_mast,tbl_site_mast, and tbl_user_mast is joined to take the details and display the list.
//Two types of sites are shown: 'Under Construction' status comes from tbl_tempsite_mast,  'Completed' status comes from 
//tbl_site_mast. Link creation for deletion/preview  is based on the status. 
//To preview site status is '1' for completed sites, '0' for under construction sites.
//To delete a site status is 'edit' for completed sites, 'new' for under construction sites. 	  

//(A)	Delete a site 	
//	  	(i)	Get the values for site type($_POST["siteType"]) and site id($_POST["siteId"])
//		(ii)	if(site type == "edit") then
//					it means you are deleting a published site
//					check tbl_site_mast if there is a record for the site_id with del_status set to 0
//					IF true then
//						remove folder workarea/sites/siteid/
//						remove folder sites/siteid
//						if it is a "simple" type site then delete sitepages/sites/siteid/
//						Delete all records from tbl_site_pages for the siteid
//						Update tbl_site_mast, set vdelStatus field to '1' for the siteid
//				    END IF
//				else
//					it means you are deleting a temporary site
//					check tbl_tempsite_mast if there is a record for the siteid
//					IF true then
//						remove folder workarea/tempsites/siteid/
//						if it is a "simple" type site then delete sitepages/tempsites/siteid/
//						Delete all records from tbl_tempsite_pages for the siteid
//						Delete from tbl_tempsite_mast for the siteid
//				    END IF
//				end if
error_reporting(0);
$user_id = ($_GET["user_id"] != ""?$_GET["user_id"]:$_POST["user_id"]);
$curTab = ($user_id > 0)?'users':'sites';

// include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";
include "includes/admin_functions.php";

if (get_magic_quotes_gpc()) {
    $_POST 		= array_map('stripslashes_deep', $_POST);
    $_GET 		= array_map('stripslashes_deep', $_GET);
    $_COOKIE 	= array_map('stripslashes_deep', $_COOKIE);
}

$begin		= ($_GET["begin"] != ""		? $_GET["begin"]		: $_POST["begin"]);
$num		= ($_GET["num"] != ""		? $_GET["num"]			: $_POST["num"]);
$numBegin	= ($_GET["numBegin"] != ""	? $_GET["numBegin"]		: $_POST["numBegin"]);
$txtSearch	= ($_GET["txtSearch"] != ""	? trim($_GET["txtSearch"])	: trim($_POST["txtSearch"]));
$cmbSearchType	= ($_GET["cmbSearchType"] != ""	? $_GET["cmbSearchType"]	: $_POST["cmbSearchType"]);
//$orderType      = ($_GET["orderType"] == "ASC"?"DESC":"ASC");
$orderType      = $_GET["orderType"];
$orderField     = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);
$fromDate   = ($_REQUEST["fromDate"])?date("Y-m-d",strtotime($_REQUEST["fromDate"])):'';
$toDate     = ($_REQUEST["toDate"])?date("Y-m-d",strtotime($_REQUEST["toDate"])):'';

if ($begin == "") {  $begin	= 0; $num = 1; $numBegin = 1; }

$sortSiteArrow 		= '<img src="../images/bgar.gif">';
$sortNameArrow 		= '<img src="../images/bgar.gif">';
$sortStatusArrow 	= '<img src="../images/bgar.gif">';
$sortDateArrow 		= '<img src="../images/bgar.gif">';


switch($orderField){
    case 'vsite_name': $sortSiteArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'user_name': $sortNameArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'status': $sortStatusArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
    case 'ddate': $sortDateArrow = '<img src="../images/'.getSortArrow($_GET["orderType"]).'">';break;
}


$_SESSION['session_edittype'] 		= "";
$_SESSION['session_published'] 		= "";
$_SESSION['session_paymentmode'] 	= "";

$message_delete = isset($_REQUEST['msg'])	?	$_REQUEST['msg']	:	"";

//#A2 starts here
//set amount for publishing
$qry	= "select vname,vvalue from tbl_lookup where vname IN('paymentsupport', 'site_price','naff_amnt')";
$result		= mysql_query($qry);
$paymentsupport	= "no";
while($row = mysql_fetch_array($result)) {
	switch($row["vname"]) {
		case site_price:
				$cost		  = (int)$row["vvalue"];
				break;
		case naff_amnt:
				$var_aff_amnt = (int)$row["vvalue"];
				break;
		case paymentsupport:
				$paymentsupport = $row["vvalue"];
				break;				
	}
}
//#A2 ends here

//If the user clicks on the delete link against a site
if($_POST["postback"] == "D") {
        $var_site_type = $_POST["siteType"];
        $var_site_id = $_POST["siteId"];

        $base_to_delete = "";
        if($var_site_type == "edit") {
                $sql = "Select vtype,nuser_id,vsite_name from tbl_site_mast where nsite_id='" . addslashes($var_site_id) . "'
                AND ndel_status='0'";
                $base_to_delete = "sites";
                $sql_delete1 = "Delete from tbl_site_pages where nsite_id='" . addslashes($var_site_id) . "'";
                $sql_delete2 = "Update tbl_site_mast set ndel_status='1' where nsite_id='" . addslashes($var_site_id) .  "'";
        }
        else {
                $sql = "Select vtype,nuser_id,vsite_name from tbl_site_mast where nsite_id='" . $var_site_id . "'";
                $base_to_delete = "tempsites";
                $sql_delete1 = "Delete from tbl_site_pages where nsite_id='" . addslashes($var_site_id) . "'";
                $sql_delete2 = "Delete from tbl_site_mast where nsite_id='" . addslashes($var_site_id) . "'";
        }
        $result = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                $var_site_category = $row["vtype"];
                if(is_dir("../workarea/". $var_site_id)){
                    remove_dir("../workarea/". $var_site_id);
                    ($var_site_type == "edit")?(remove_dir("../sites/" . $var_site_id)):"";
                    ($row["vtype"] == "simple")?(remove_dir("../sitepages/" . $base_to_delete . "/" . $var_site_id)):"";
                }
                mysql_query($sql_delete1) or die(mysql_error());
                mysql_query($sql_delete2) or die(mysql_error());
                $message_delete = "<font color='green'><br>" . $row["vsite_name"] .DELETED_SUCCESS_MSG."</font><br>&nbsp;";
        }
        else {
                $message_delete = "<font color='#FF0000'><br>".DELETE_MSG."</font><br>&nbsp;";
        }
} //END IF Delete

//#A1 starts here
// the below code is to enable administrator to make a site as PAID
// -----------------------------OLD CODE------------------------------------
//if($_POST["postback"] == "MP") {
//	$sitetype	 	= $_POST["siteType"];
//	$template_type          = $_POST["tempType"];
//	$tmpsiteid 		= $_POST["siteId"];
//	//$tmpsiteid 		= $_POST["tempId"];
//	$userid 		= $_POST["userId"];
//
//	//payment flag = default to false
//	$bool_payment	=	false;
//	
//	//check for payment data for the temporary site id in payment table
//	// if true then message is shown saying 'payment over for site'
//	if ($sitetype == "new")
//		$sql = "Select * from tbl_payment where ntempsite_id='" . $tmpsiteid . "'";
//	else
//		$sql = "Select * from tbl_payment where nsite_id='" . $tmpsiteid . "'";
//	
//	$result = mysql_query($sql) or die(mysql_error());
//	if(mysql_num_rows($result) > 0) {
//			$bool_payment	= true;
//			$message_delete	= "This site is already paid!";
//	} else {
//		$sql = "select * from tbl_tempsite_mast where ntempsite_id='".addslashes($tmpsiteid)."'";
//		$result = mysql_query($sql);
//		if(mysql_num_rows($result) > 0) {
//			$row = mysql_fetch_array($result);
//			if(! is_dir("../workarea/tempsites/".$tmpsiteid)){
//			  $message_delete = "Site temporary removed. Please contact administrator to resolve issue!";
//			  
//			} elseif ($row["nuser_id"] != $userid) {
//			  $message_delete = "Incorrect user id as input. Please do not manipulate the url.";
//			  
//			} else {
//			//Section WFM
//			//Uncomment WFM section for Wells Fargo merchant section
//				$Cust_ip	= getClientIP();
//				$Cust_id	= uniqid("Cust");
//			//END WFM
//	
//				$var_id	= process_after_payment($template_type, $tmpsiteid, $userid,
//												$cost, $var_aff_amnt, $Cust_id);
//				
//				if ($var_id > 0)
//				  $message_delete = "Successfully made as Paid!";
//			}
//		}
//	}
//}
//-----------------------------------------------------
if($_POST["postback"] == "MP") {
    $siteId 		= $_POST["siteId"];
    $userid 		= $_POST["userId"];
    $cost               = getSettingsValue('site_price');
    $type               = 'Phoneorder';
    $Cust_id=uniqid("Cust");
    $insertPaymentData = "INSERT INTO tbl_payment(nsite_id,nuser_id,namount,ddate,vpayment_type,vuniqid) Values(
                            '" . mysql_real_escape_string($siteId) . "',
                            '" . mysql_real_escape_string($userid) . "',
                            '" . $cost . "',
                            now(),
                            '" . mysql_real_escape_string($type) . "',
                           '" . $Cust_id . "')";
    mysql_query($insertPaymentData) or die(mysql_error());
    $message_delete = "<font color='green'><br>".PAYMENT_STATUS_UPADATED_MSG.".</font><br>&nbsp;";
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
<link rel="stylesheet" href="../style/jquery-ui.css" />
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui.js"></script>
<script language="JavaScript" type="text/JavaScript">
$(document).ready(function () {
    $( "#fromDate" ).datepicker();
    $( "#toDate" ).datepicker();
});
function clickSearch(){
    document.frmSites.postback.value="S";
    document.frmSites.action="sitemanager.php";
    document.frmSites.submit();
}
//---------------Old code-------------
//function clickPaid(siteid,templateid,type,advanced, userid){
//        if(confirm("Are you sure you want to make this site as Paid?")) {
//                var frmId =document.frmSites;
//                frmId.postback.value="MP";
//                frmId.tempType.value=advanced;
//                frmId.siteType.value=(type == 1)?"edit":"new";
//                frmId.siteId.value=siteid;
//                frmId.tempId.value=templateid;
//                frmId.userId.value=userid;
//                frmId.action="sitepayment.php";
//                frmId.method="post";
//                frmId.submit();
//        }
//}
//-----------------------------------------------------
function clickPaid(siteid,userid){
    if(confirm("<?php echo CONF_SITE_PAID;?>")) {
        var frmId               =   document.frmSites;
        frmId.postback.value    =   "MP";
        //frmId.tempType.value  =advanced;
        //frmId.siteType.value    =   (type == 1)?"edit":"new";
        frmId.siteId.value      =   siteid;
        //frmId.tempId.value      =   templateid;
        frmId.userId.value      =   userid;
        //frmId.action            =   "sitepayment.php";
        frmId.method            =   "post";
        frmId.submit();   
    }
    else{
        return false;
    }
}
function clickDelete(siteid,templateid,type,advanced){
        if(confirm("<?php echo CONF_SITE_DELETE;?>")) {
                var frmId =document.frmSites;
                frmId.postback.value="D";
                frmId.siteType.value=(type == 1)?"edit":"new";
                frmId.siteId.value=siteid;
                frmId.action="sitemanager.php";
                frmId.method="post";
                frmId.submit();
        }
}

function showpreview(id,status,type,template,user){
    winname="sitepreview";
    winurl="showsitepreview.php?id=" + id +"&status="+status+"&type="+type+"&template="+template+"&user="+user;
    window.open(winurl,winname,'');
 }


</script>
<?php
$linkArray = array( USER   =>'admin/usermanager.php',
                    SITES  =>'admin/sitemanager.php');
?>
<div class="admin-pnl">
    <?php echo ($user_id > 0)?getBreadCrumb($linkArray):''; ?>
    <h2><?php echo SITES;?></h2>
    <div>
        <div style="font-size: 13px;"><?php echo $message;?></div>
    </div>
    
<div class="content-tab-pnl">
<form name="frmSites" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">
<input type="hidden" name="siteType" id="siteType" value="">
<input type="hidden" name="tempType" id="tempType" value="">
<input type="hidden" name="siteId" id="siteId" value="">
<input type="hidden" name="tempId" id="tempId" value="">
<input type="hidden" name="userId" id="userId" value="">

<?php
$qryopt1 = "";
$qryopt2 = "";
$qryopt3 = "";

if ($txtSearch != "") {
    if ($cmbSearchType == "sitename") {
        $qryopt1 .= "  AND sm.vsite_name like '%" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND sm.vsite_name like '%" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "date") {
        $qryopt1 .= "  AND DATE_FORMAT(sm.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND DATE_FORMAT(sm.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "username") {
        $qryopt3 .= " AND (u.vuser_login like '" . addslashes($txtSearch) . "%' OR u.vuser_login LIKE '" . addslashes($txtSearch) . "%')";
    } 
}


if($fromDate!='' || $toDate!='' ){
    if($fromDate!=''){
        $qryopt1 .= " AND Date_Format(sm.ddate,'%Y-%m-%d') >= '" . addslashes($fromDate)."'";
        $qryopt2 .= " AND Date_Format(sm.ddate,'%Y-%m-%d') >= '" . addslashes($fromDate)."'";
    }
    if($toDate!=''){
        $qryopt1 .= " AND Date_Format(sm.ddate,'%Y-%m-%d') <= '" . addslashes($toDate)."'";
        $qryopt2 .= " AND Date_Format(sm.ddate,'%Y-%m-%d') <= '" . addslashes($toDate)."'";
    }
}

//------------------OLD QUERY-----------------------------------
// $sql="SELECT nsite_id, nuser_id, vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate FROM tbl_site_mast  WHERE nuser_id='".$_SESSION["session_userid"]."' And vdelstatus !='1'" . $qryopt . "  order by ddate DESC   ";
//$sql = "select IFNULL(ts.ntempsite_id,si.nsite_id) as 'nsite_id',IFNULL(ts.ntemplate_id,si.ntemplate_id) as 'ntemplate_id',
//IFNULL(ts.vsite_name,si.vsite_name) as 'vsite_name',IFNULL(Date_Format(ts.ddate,'%m/%d/%Y'),
//Date_Format(si.ddate,'%m/%d/%Y')) as 'ddate',CASE si.is_published WHEN 1 THEN 'Published' WHEN 0 THEN 'Draft' END  as 'status',
//IFNULL(ts.vtype,si.vtype) as 'vtype', IFNULL(um.vuser_login,um2.vuser_login) as 'user_name', IFNULL(um.nuser_id,um2.nuser_id) as 'user_id'
//from dummy d left join tbl_tempsite_mast ts ON(d.num=0 " . $qryopt1 . ")
//left join tbl_site_mast si on(d.num=1  AND si.ndel_status='0'  " . $qryopt2 . ")
//left join tbl_user_mast um on ts.nuser_id = um.nuser_id left join tbl_user_mast um2 on si.nuser_id = um2.nuser_id 
//
// Where (ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL)" . $qryopt3;
//-----------------------------------------------------------


 $sql   =   "SELECT sm.nsite_id,sm.ntemplate_id,sm.vsite_name,Date_Format(sm.ddate,'%m/%d/%Y') AS ddate,
                CASE sm.is_published WHEN 1 THEN '".PUBLISHED."' WHEN 0 THEN '".DRAFT."' END  as 'status',
                u.vuser_name as 'user_name',u.vuser_login,u.nuser_id,p.npayment_id,vpayment_type    
                FROM tbl_site_mast AS sm 
                LEFT JOIN  tbl_user_mast AS u ON u.nuser_id = sm.nuser_id 
                LEFT JOIN tbl_payment AS p ON p.nsite_id=sm.nsite_id 
                WHERE sm.ndel_status='0'  ".$qryopt1." ".$qryopt2." ".$qryopt3;
            
            
//Date_Format(si.ddate,'%m/%d/%Y')) as 'ddate'";
if($orderField!=""){
    if($orderField=='ddate') $orderSqlField = "Date_Format(sm.ddate,'%Y-%m-%d')";
    else $orderSqlField = $orderField;
    $sql.=" order by $orderSqlField $orderType";
}else{
    $sql.=" order by sm.ddate DESC";
}
//echo $sql;
//ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL 
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
$navigate = pageBrowser($totalrows, 5, $pageCount, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&orderField=$orderField&orderType=$orderType", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);

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
                                                <td colspan="4" align="center" width="100%"><?php echo($message_delete); ?></td>
                                            </tr>
                                            <tr>
                                                <td  class="listing126">
                                                    <div class="admin-search-pnl">
                                                        <label><?php echo SEARCH;?></label>
                                                        <select name="cmbSearchType" class="selectbox">
                                                            <option value="sitename" <?php if ($cmbSearchType == "sitename" || $cmbSearchType == "") {
                                                                echo("selected");
                                                                    } ?>><?php echo SM_SITE_NAME;?></option>
                                                            <!--option value="date"  <?php //if ($cmbSearchType == "date" || $cmbSearchType == "") {
                                                                //echo("selected");
                                                                    //} ?>>Date</option-->
                                                            <option value="username"  <?php if ($cmbSearchType == "username") {
                                                                echo("selected");
                                                                    } ?>><?php echo USERNAME;?></option>
                                                        </select> &nbsp;
                                                        <input type="text" name="txtSearch" size="20" maxlength="50"
                                                               value="<?php echo($txtSearch); ?>"
                                                               onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                                               class="textbox">&nbsp;
                                                        <label><?php echo MAIL_FROM;?></label>
                                                        <input name="fromDate"  id="fromDate" type="text" value="<?php echo getDateFormat($fromDate);?>">
                                                        <img src="../themes/Coastal-Green/calendar-icon.png" alt="">
                                                        <label><?php echo MAIL_TO;?> </label>
                                                        <input name="toDate" id="toDate" type="text" value="<?php echo getDateFormat($toDate);?>">
                                                        <img src="../themes/Coastal-Green/calendar-icon.png" alt="">
                                                        <a href="javascript:clickSearch();" class="btn05"><?php echo SEARCH;?></a>
                                                        <a href="<?php echo "site_excel.php?cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&fromDate=$fromDate&toDate=$toDate&orderField=$orderField&orderType=$orderType" ?>" class="grey-btn03 ryt"><?php echo EXPORT;?></a>
                                                        <div class="clear"></div>
                                                    </div>
                                                </td></tr>

                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="admin-table-list">
                                <tr>
                                    <th width="20%"><a href="<?php echo BASE_URL?>admin/sitemanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=vsite_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SM_SITE_NAME;?><?php echo $sortSiteArrow;?></a></th>
                                    <th width="15%"><a href="<?php echo BASE_URL?>admin/sitemanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=user_name&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo USER;?><?php echo $sortNameArrow;?></a></th>
                                    <th width="22%"><a href="<?php echo BASE_URL?>admin/sitemanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=ddate&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SM_DATE_CREATED;?><?php echo $sortDateArrow;?></a></th>
                                    <th width="15%"><a href="<?php echo BASE_URL?>admin/sitemanager.php?cmbSearchType=<?php echo $cmbSearchType?>&txtSearch=<?php echo $txtSearch?>&orderField=status&orderType=<?php echo ($_GET["orderType"] == "ASC")?"DESC":"ASC"?>&begin=<?php echo $begin ?>&num=<?php echo $num ?>&numBegin=<?php echo $numBegin ?>"><?php echo SM_STATUS;?><?php echo $sortStatusArrow;?></a></th>
                                    <th width="10%"><?php echo PAID;?></th>
                                    <th width="7%"><?php echo DELETE;?></th>
                                    <th width="11%"><?php echo PREVIEW;?></th>
                                </tr>
                                <?php
                                // loop and display the limited records being browsed
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) { 
                                   // echopre($arr);?>
                                <tr   class=background class='text'>
                                    <td align='left' style='word-break: break-all;'> &nbsp;<?php echo " <a href='../workarea/sites/$arr[nsite_id]/index.html' target='_blank'>". stripslashes($arr["vsite_name"])."</a>"; ?></td>
                                    <td  align='center' style='word-break: break-all;'>&nbsp;<?php echo  " <a href='sitemanager.php?user_id=".$arr["nuser_id"]."&begin=0&num=1&numBegin=1&cmbSearchType=username&txtSearch=".$arr["vuser_login"]."' target='_blank'>".stripslashes($arr["user_name"])."</a>"; ?> </td>
                                    <td  align='center' style='word-break: break-all;'>&nbsp; <?php echo stripslashes($arr["ddate"]) ?></td>
                                    <td  style='word-break: break-all;'><?php echo $arr["status"]?> </td>
                                        <?php
                                        //$payStatus = $arr["status"] == "Completed" ? "Paid" : ($paymentsupport == "no" ? "Free" : "<a href=\"#\" onClick=\"javascript:clickPaid(" . $arr["nsite_id"] . "," . $arr["ntemplate_id"] . "," . (($arr["status"] == "Completed")?1:0) . ",'" . $arr["vtype"] . "'," . $arr["user_id"] . ");\" style=\"text-decoration:none;\" class=subtext>Make Paid</a>");
//                                        $payStatus = $arr["status"] == "Published" ?($paymentsupport == "no") ? "Free":"Paid" : "NA";
//                                        if($paymentsupport == "no"){
//                                           $payStatus = $arr["status"] == "Published" ?"Free":"NA";
//                                        }
//                                        else{
//                                             $payStatus = $arr["status"] == "Published" ?"Paid":"NA";
//                                        }
                                       if($arr["vpayment_type"]=='Free'){
                                           $payStatus = FREE;
                                       }
                                       else{
                                           if($arr["npayment_id"]>0){
                                             $payStatus = PAID;  
                                           }
                                           else{
                                             $payStatus = "<a href='#' onClick='javascript:clickPaid(". $arr['nsite_id'] . "," . $arr['nuser_id'] .")'  style='text-decoration:none;' class=subtext>".MAKE_PAID."</a>";  
                                           }
                                           
                                       }
                                        echo "<td  style='word-break: break-all;'>" . $payStatus . " </td>";
                                        echo "<td  >&nbsp;<a href=\"#\" onClick=\"javascript:clickDelete(" . $arr["nsite_id"] . "," . $arr["ntemplate_id"] . "," . (($arr["status"] == "Completed")?1:0) . ",'" . $arr["vtype"] . "');\" style=\"text-decoration:none;\" class=subtext>".DELETE."</a></td>";
                                        //echo "<td >&nbsp;<a href=javascript:showpreview('" . $arr["nsite_id"] . "','" . (($arr["status"] == "Completed")?1:0) . "','" . $arr["vtype"] . "','" . $arr["ntemplate_id"] . "','".$_SESSION["session_userid"]."') class=subtext>Preview</a></td>";
                                       echo "<td >&nbsp;<a href='../workarea/sites/$arr[nsite_id]/index.html' target='_blank'>".PREVIEW."</a></td>";
                                        
                                        echo "</tr>";
                                        $counter++;
                                    }
                                    ?>
                            </table>
                                <?php if($totalrows >$pageCount) { ?>
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

                </td>
                </tr>
                </table>
            
        
</form>
</div>
</div>
<?php
include "includes/adminfooter.php";
?>