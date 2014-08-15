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

//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/admin_functions.php"; 
?>
<?php
$txtSearch  =($_GET["txtSearch"] != ""?$_GET["txtSearch"]:$_POST["txtSearch"]);
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
$orderType  = ($_GET["orderType"] == "ASC"?"DESC":"ASC");
$orderField = ($_GET["orderField"] != ""?$_GET["orderField"]:$_POST["orderField"]);
$fromDate   = ($_REQUEST["fromDate"])?date("Y-m-d",strtotime($_REQUEST["fromDate"])):'';
$toDate     = ($_REQUEST["toDate"])?date("Y-m-d",strtotime($_REQUEST["toDate"])):'';
$qryopt1 = "";
$qryopt2 = "";
$qryopt3 = "";


/*
if ($txtSearch != "") {
    if ($cmbSearchType == "sitename") {
        $qryopt1 .= "  AND ts.vsite_name like '" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND si.vsite_name like '" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "date") {
        $qryopt1 .= "  AND DATE_FORMAT(ts.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND DATE_FORMAT(si.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "username") {
        $qryopt3 .= " AND (um.vuser_login like '" . addslashes($txtSearch) . "%' OR um2.vuser_login LIKE '" . addslashes($txtSearch) . "%')";
    }
}
if($fromDate!='' || $toDate!='' ){
    if($fromDate!=''){
        $qryopt1 .= " AND ts.ddate >= '" . addslashes($fromDate)."'";
        $qryopt2 .= " AND si.ddate >= '" . addslashes($fromDate)."'";
    }
    if($toDate!=''){
        $qryopt1 .= " AND ts.ddate <= '" . addslashes($toDate)."'";
        $qryopt2 .= " AND si.ddate <= '" . addslashes($toDate)."'";
    }
}
*/




if ($txtSearch != "") {
    if ($cmbSearchType == "sitename") {
        $qryopt1 .= "  AND sm.vsite_name like '" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND sm.vsite_name like '" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "date") {
        $qryopt1 .= "  AND DATE_FORMAT(sm.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        $qryopt2 .= "  AND DATE_FORMAT(sm.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
    } elseif ($cmbSearchType == "username") {
        $qryopt3 .= " AND (u.vuser_login like '" . addslashes($txtSearch) . "%' OR u.vuser_login LIKE '" . addslashes($txtSearch) . "%')";
    } 
}

if($fromDate!='' || $toDate!='' ){
    if($fromDate!=''){
        $qryopt1 .= " AND sm.ddate >= '" . addslashes($fromDate)."'";
        $qryopt2 .= " AND sm.ddate >= '" . addslashes($fromDate)."'";
    }
    if($toDate!=''){
        $qryopt1 .= " AND sm.ddate <= '" . addslashes($toDate)."'";
        $qryopt2 .= " AND sm.ddate <= '" . addslashes($toDate)."'";
    }
}

 $sql   =   "SELECT sm.nsite_id,sm.ntemplate_id,sm.vsite_name,Date_Format(sm.ddate,'%m/%d/%Y') AS ddate,
                CASE sm.is_published WHEN 1 THEN 'Published' WHEN 0 THEN 'Draft' END  as 'status',
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
// echo $sql;






/*

$sql = "select IFNULL(ts.ntempsite_id,si.nsite_id) as 'nsite_id',IFNULL(ts.ntemplate_id,si.ntemplate_id) as 'ntemplate_id',
IFNULL(ts.vsite_name,si.vsite_name) as 'vsite_name',IFNULL(Date_Format(ts.ddate,'%m/%d/%Y'),
Date_Format(si.ddate,'%m/%d/%Y')) as 'ddate',IF( ISNULL(ts.ntempsite_id), 'Completed', 'Under Construction' ) as 'status',
IFNULL(ts.vtype,si.vtype) as 'vtype', IFNULL(um.vuser_login,um2.vuser_login) as 'user_name', IFNULL(um.nuser_id,um2.nuser_id) as 'user_id'
from dummy d left join tbl_tempsite_mast ts ON(d.num=0 " . $qryopt1 . ")
left join tbl_site_mast si on(d.num=1  AND si.ndel_status='0'  " . $qryopt2 . ")
left join tbl_user_mast um on ts.nuser_id = um.nuser_id left join tbl_user_mast um2 on si.nuser_id = um2.nuser_id
 Where (ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL)" . $qryopt3;
if($orderField!=""){
    $sql.=" order by $orderField $orderType";
}else{
    $sql.=" order by ts.ddate DESC,si.ddate DESC";
}
*/
 
 
// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

//execute the new query with the appended SQL bit returned by the function
$rs = mysql_query($sql) or die(mysql_error());

$xldata	=	'<table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="support2" colspan="4">Sites &nbsp;&nbsp;</td>
                          </tr>
                         </table>
                         <table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="ba1">
                              <table width="100%"  border="1" cellspacing="1" cellpadding="3">
                              <tr>
                                    <th width="4%" valign="top">#</th>
                                    <th width="15%" valign="top">Site Name</th>
                                    <th width="20%" >User</th>
                                    <th width="25%" >Date Created</th>
                                    <th width="15%" >Status</th>
                                </tr>';
                                    if(mysql_num_rows($rs) > 0 )
                                    {
					$i = 0;
					while($row = mysql_fetch_array($rs))
					{ $i++;
                                            $xldata	=	$xldata.'
                                            <tr bgcolor="#FFFFFF" class="maintext">
                                            <td>'.stripslashes($i).'</td>
                                            <td>'.stripslashes($row["vsite_name"]).'</td>
                                            <td>'.stripslashes($row["user_name"]).'</td>
                                            <td>'.stripslashes($row["ddate"]).'</td>
                                            <td>'.stripslashes($row["status"]).'</td></tr>';
				       }
                               }
                                $xldata	= $xldata.'        </table>
                                        </td>
                                      </tr>
                                    </table>';
header("Content-type: application/ms-excel");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=\"siteReport.xls\"");
	echo $xldata;
         ?>

      