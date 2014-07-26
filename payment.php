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
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$tmpsiteid=$_SESSION['session_currenttempsiteid'];
$templateid=$_SESSION['session_currenttemplateid'];
$userid=$_SESSION["session_userid"];
$qry="select vvalue from tbl_lookup where vname='site_price'";
$result=mysql_query($qry);
$row=mysql_fetch_array($result);
$_SESSION["session_totalamount"]=(int)$row["vvalue"];
$errormessage="";
$qry="select * from tbl_tempsite_mast where ntempsite_id='".$tmpsiteid."' and nuser_id='".$userid."' and ntemplate_id='".$templateid."' and vtype='simple'";
if( mysql_num_rows( mysql_query($qry))>0){
    if(! is_dir("./workarea/tempsites/".$tmpsiteid)){
          $errormessage="Site temporary removed.please contact administrator to resolve issue!";
    }
}else{
   $errormessage="You are not authorized to view this page!";
}
if($errormessage !=""){
  echo $errormessage;
  exit;
}
if ($_POST['postbackCC'] == "P") {
    $FirstName = $_POST["txtFirstName"];
    $LastName = $_POST["txtLastName"];
    $Address = $_POST["txtAddress"];
    $City = $_POST["txtCity"];
    $State = $_POST["txtState"];
    $Zip = $_POST["txtPostal"];
    $CardNum = $_POST["txtccno"];
    $Email = $_POST["txtEmail"];
    $CardCode = $_POST["txtcvv2"];
    $Country = $_POST["cmbCountry"];
    $Month = $_POST["txtMM"];
    $Year = $_POST["txtYY"];

    $cc_tran = "";
    $cc_flag = false;
    $cost = $_SESSION["session_totalamount"];
    require("credit_inte.php"); // if you do not have cURL support comment this line and uncomment the below section

    if (($cc_flag == true)) {
        $_SESSION['session_paymentmode'] = "success";
        if ($_SESSION['session_siteid'] == "") {
            //insert into tbl_site_master
            //echo "<br>magiqts==".get_magic_quotes_runtime();
            //set_magic_quotes_runtime(1);
            //echo "<br>magiqts1==".get_magic_quotes_runtime();
            $qry = "select * from tbl_tempsite_mast where ntempsite_id='" . $tmpsiteid . "'";
            $rs = mysql_query($qry);
            $row = mysql_fetch_array($rs);

            /* if(get_magic_quotes_runtime()==1){

              $row['vlinks']=$row['vlinks'];
              $row['vsub_sitelinks']=$row['vsub_sitelinks'];
              }else{
              $row['vlinks']=addslashes($row['vlinks']);
              $row['vsub_sitelinks']=addslashes($row['vsub_sitelinks']);
              } */

            $qry1 = " insert into tbl_site_mast(nsite_id,vsite_name,nuser_id,ntemplate_id,
                    vtype,vtitle,vmeta_description,vmeta_key,vlogo,vcompany,vcaption,vlinks,
                    vcolor,vemail,ddate,vdelstatus,vsub_logo,vsub_caption,
                    vsub_company,vsub_sitelinks) values(";
            $qry1 .="'','" . $row['vsite_name'] . "','" . $row['nuser_id'] . "','" . $row['ntemplate_id'] . "','" . $row['vtype'] . "',";
            $qry1 .="'" . $row['vtitle'] . "','" . $row['vmeta_description'] . "','" . $row['vmeta_key'] . "','" . $row['vlogo'] . "','" . $row['vcompany'] . "',";
            $qry1 .="'" . $row['vcaption'] . "','" . $row['vlinks'] . "','" . $row['vcolor'] . "','" . $row['vemail'] . "',";
            $qry1 .="'" . $row['ddate'] . "','" . $row['vdelstatus'] . "','" . $row['vsub_logo'] . "','" . $row['vsub_caption'] . "','" . $row['vsub_company'] . "',";
            $qry1 .="'" . $row['vsub_sitelinks'] . "'";
            $qry1 .=")";

            mysql_query($qry1);
            $_SESSION['session_siteid'] = mysql_insert_id();

            $siteid = $_SESSION['session_siteid'];
            //insert into payment table
            $sql = "Insert into tbl_payment(npayment_id,ntempsite_id,nsite_id,nuser_id,namount,ddate,
                    vpayment_type,vtxn_id) Values('',
                    '" . $_SESSION['session_currenttempsiteid'] . "',
                    '" . $_SESSION['session_siteid'] . "',
                    '" . $_SESSION["session_userid"] . "',
                    '" . $_SESSION['session_totalamount'] . "',
                    now(),
                    'CreditCard',
                    '" . $cc_tran . "')";
            mysql_query($sql);

            //remove from tbl_tempsite_mast
            $qrydelete = "delete from tbl_tempsite_mast where ntempsite_id='" . $tmpsiteid . "'";
            mysql_query($qrydelete);

            //insert into site pages
            $qryinserttopages = "insert into tbl_site_pages(nsp_id,nsite_id,vpage_name,vpage_title,vpage_type,vtype) values ";
            $qry = "select * from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "' order by ntempsp_id";
            $rs1 = mysql_query($qry);

            while ($row1 = mysql_fetch_array($rs1)) {
                $qryinserttopages .="('','" . $_SESSION['session_siteid'] . "','" . $row1['vpage_name'] . "','" . $row1['vpage_title'] . "','" . $row1['vpage_type'] . "','" . $row1['vtype'] . "'),";
            }
            $qryinserttopages = substr($qryinserttopages, 0, -1);
            mysql_query($qryinserttopages);
            //delete from tbl_tempsite_pages
            $qrydelete = "delete from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "'";
            mysql_query($qrydelete);
            //copy site pages
            if (!is_dir("./sites/$siteid")) {
                mkdir("./sites/$siteid", 0777);
            }

            copydirr("./sitepages/tempsites/$tmpsiteid/", "./sites/$siteid", 0777, false);
            //copy resource.txt
            copy("./workarea/tempsites/" . $tmpsiteid . "/resource.txt", "./sites/$siteid/resource.txt");
            chmod("./sites/$siteid/resource.txt", 0777);


            SaveSitePreview($userid, $templateid, $tmpsiteid, $siteid, "Yes", ".", "Save");

            //delete from tempsites,sites,tempsitepages
            remove_dir("./workarea/tempsites/$tmpsiteid");
            remove_dir("./workarea/sites/$siteid");
            remove_dir("./sitepages/tempsites/$tmpsiteid");
            //clear session
        }

        header("location:downloadsite.php");
        exit;
    } else {
        $_SESSION['session_paymentmode'] = "failure";
    }
}
include "includes/userheader.php";
?>
<script>
function onlyNumeric(){
         if (event.keyCode<48||event.keyCode>57)
         return false;
}
function clickPay() {
        var frm = document.frmCCInfo;
                var flag = false;

                 if (frm.txtccno.value.length == 0 || frm.txtMM.value.length == 0 || frm.txtYY.value.length == 0) {
                                alert('Please enter a valid credit card number and expiry date.');
                        }
                        else {
                                flag = true;
                        }
                if (flag == true) {

                        frm.postbackCC.value="P";

                        frm.method="post";
                        frm.action="payment.php";

                        frm.submit();
                }
}
function checkNumber(t) {
        if(t.value.length == 0 ||  isNaN(t.value) || t.value.substr(0,1) == " " || parseInt(t.value) < 0) {
                t.value="";
        }
}
</script>

<table width="82%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align=center><img src="./images/cstep5.gif"><br></td>
                </tr>
                <tr>
                    <td align=center>&nbsp;</td>
                </tr>
                <tr>
                    <td bgcolor="#FFFFFF">
                        <!-- Main section starts here-->
                        <table width="80%" bgcolor="#FFFFFF" border=0 align=center>
                            <tr>
                                <td>
                                    <form name="frmCCInfo" action="" method="POST">
                                        <input type="hidden" name="postbackCC" value="">
                                        <?php
                                        $totalamt = $_SESSION["session_totalamount"];
                                        require("./authorizepayment.php");
                                        ?>
                                    </form>
                                </td>
                                                              
                            </tr>
                        </table>
                        <!-- Main section ends here-->
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
?>