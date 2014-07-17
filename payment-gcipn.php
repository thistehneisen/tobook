<?php
$details = $_REQUEST; 
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/sitefunctions.php";
$cc_tran 		= $_REQUEST['google-order-number'];
$cost 			= $_REQUEST['order-total'];
$userDet 		= $_REQUEST['shopping-cart_merchant-private-data'];
$userDetails 	= explode(":", $userDet);
$userId 		= $userDetails[1];
$siteId  		= $userDetails[0];
if($cc_tran != '' && $cost != '' && $userId != '' && $siteId != '') {
//$paymentData = savePaymentDetails('GoogleCheckout',$cost,$cc_tran);

   
    $paymentStatus = getPaymentStatusByUser($siteId,$userId);

    if($paymentStatus <= 0) {
        $Cust_id=uniqid("Cust");

        $insertPaymentData = "INSERT INTO tbl_payment(nsite_id,nuser_id,namount,ddate,vpayment_type,vtxn_id,vuniqid) Values(
                                '" . mysql_real_escape_string($siteId) . "',
                                '" . mysql_real_escape_string($userId) . "',
                                '" . $cost . "',
                                now(),
                                '" . mysql_real_escape_string('GoogleCheckout') . "',
                                '" . $cc_tran . "',
                                '" . $Cust_id . "')";
        mysql_query($insertPaymentData) or die(mysql_error());
        $invoiceId = mysql_insert_id();
        $paymentData = $invoiceId;
    }
    


  	$userId      = $_SESSION['session_userid'];
    $userDetails = getUserDetails($userId);
     
    // Send Invoice Mail
    sendInvoiceMail($userDetails,$paymentData,$cost,$cc_tran);
    
    //pay affiliate
     payaffiliate($userid,$var_aff_amnt);
  
}
?>    