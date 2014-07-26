<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish<girish@armia.com>	        		              |
// +----------------------------------------------------------------------+
//Publish site page
//
//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/sitefunctions.php";


$paymentType = getSettingsValue('paymentsupport');

if($paymentType !="no") {
    header("location:../index.php");
    exit;
}

$siteId = $_SESSION['siteDetails']['siteId'];

// Save Payment Details
savePaymentDetails('Free');

include "includes/userheader.php";
?>

<table width="50%">
    <tr>
        <td colspan="2" align="center" class="maintext">
            <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<b><br>
            <a href="<?php echo 'downloadsite.php?sid='.$siteId ?>">Click here</a> to proceed further.</b>
            <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
?>