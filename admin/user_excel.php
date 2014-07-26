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
        if($cmbSearchType == "name"){
                $qryopt .= "  AND vuser_name like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "email"){
                $qryopt .= "  AND vuser_email like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "date"){
                $qryopt .= "  AND date_format(duser_join,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        }
}
if($fromDate!='' && $toDate!='' ){
    $qryopt .= "  AND duser_join >= '" . addslashes($fromDate). "' AND duser_join <= '" . addslashes($toDate)."'";
}

$sql="select nuser_id, vuser_login,vuser_name,vuser_email,vuser_phone,Date_Format(duser_join,'%m/%d/%Y') as date from tbl_user_mast where vdel_status='0' " . $qryopt;
if($orderField!=""){
    $sql.=" order by $orderField $orderType";
}else{
    $sql.=" order by date DESC";
}

//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

//execute the new query with the appended SQL bit returned by the function
$rs = mysql_query($sql) or die(mysql_error());

$xldata	=	'<table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="support2" colspan="4">User  &nbsp;&nbsp;</td>
                          </tr>
                         </table>
                         <table width="100%"  border="1" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="ba1">
                              <table width="100%"  border="1" cellspacing="1" cellpadding="3">
                              <tr>
                                    <th width="4%" valign="top">#</th>
                                    <th width="15%" valign="top">Login</th>
                                    <th width="20%" >Name</th>
                                    <th width="25%" >EMail</th>
                                    <th width="15%" >Phone</th>
                                    <th width="10%" >Date</th>
                                </tr>';
                                    if(mysql_num_rows($rs) > 0 )
                                    {
					$i = 0;
					while($row = mysql_fetch_array($rs))
					{ $i++;
                                            $xldata	=	$xldata.'
                                            <tr bgcolor="#FFFFFF" class="maintext">
                                            <td>'.stripslashes($i).'</td>
                                            <td>'.stripslashes($row["vuser_login"]).'</td>
                                            <td>'.stripslashes($row["vuser_name"]).'</td>
                                            <td>'.stripslashes($row["vuser_email"]).'</td>
                                            <td>'.stripslashes($row["vuser_phone"]).'</td>
                                            <td>'.stripslashes($row["date"]).'</td></tr>';
				       }
                                       //end while

                                }
                                $xldata	= $xldata.'        </table>
                                        </td>
                                      </tr>
                                    </table>';

	header("Content-type: application/ms-excel");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=\"userReport.xls\"");
	echo $xldata;
         ?>

      