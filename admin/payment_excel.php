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
    $qryopt .= "  AND p.ddate >= '" . addslashes($fromDate)."'";
    if($toDate!='')
    $qryopt .= " AND p.ddate <= '" . addslashes($toDate)."'";
    //$qryopt .= "  AND p.ddate >= '" . addslashes($fromDate). "' AND p.ddate <= '" . addslashes($toDate)."'";
}
//get the list of payments done
$sql="SELECT u.vuser_name,p.npayment_id,p.nsite_id,p.namount,p.vpayment_type,p.vtxn_id,Date_Format(p.ddate,'%m/%d/%Y') as ddate, s.vsite_name FROM tbl_payment p,tbl_site_mast s,tbl_user_mast u WHERE  p.nuser_id=u.nuser_id AND s.nsite_id=p.nsite_id" . $qryopt;

if($orderField!=""){
    $sql.=" order by $orderField $orderType";
}else{
    $sql.=" order by p.ddate DESC";
}

//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

//execute the new query with the appended SQL bit returned by the function
$rs = mysql_query($sql) or die(mysql_error());

$xldata	=	'<table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="support2" colspan="4">Payments  &nbsp;&nbsp;</td>
                          </tr>
                         </table>
                         <table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="ba1">
                              <table width="100%"  border="1" cellspacing="1" cellpadding="3">
                              <tr>
                                    <th width="4%" valign="top">#</th>
                                    <th width="15%" valign="top">User Name</th>
                                    <th width="20%" >Site Name</th>
                                    <th width="25%" >Date</th>
                                    <th width="15%" >Payment Type</th>
                                    <th width="10%" >Trans Id</th>
                                    <th width="10%" >Amount</th>
                                </tr>';
                                    if(mysql_num_rows($rs) > 0 )
                                    {
					$i = 0;
					while($row = mysql_fetch_array($rs))
					{ $i++;
                                            $xldata	=	$xldata.'
                                            <tr bgcolor="#FFFFFF" class="maintext">
                                            <td>'.stripslashes($i).'</td>
                                            <td>'.stripslashes($row["vuser_name"]).'</td>
                                            <td>'.stripslashes($row["vsite_name"]).'</td>
                                            <td>'.stripslashes($row["ddate"]).'</td>
                                            <td>'.stripslashes($row["vpayment_type"]).'</td>
                                            <td>'.stripslashes($row["vtxn_id"]).'&nbsp;</td>
                                            <td>'.stripslashes($row["namount"]).'</td></tr>';
				       }
                                       //end while

                                }
                                $xldata	= $xldata.'        </table>
                                        </td>
                                      </tr>
                                    </table>';
        ob_start();
        header("Content-type: application/ms-excel");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=\"paymentReport.xls\""); 
	echo $xldata;
        exit;
         ?>

      