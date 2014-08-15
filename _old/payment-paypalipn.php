<?php

include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/sitefunctions.php";


$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

 $paypal_sandbox   = getSettingsValue('paypal_sandbox');
 mail('naseema.n@armiasystems.com','IPN Result-SANDBOX', $paypal_sandbox);

 if($paypal_sandbox =="YES"){
     // for developement mode
     $url="https://www.sandbox.paypal.com/cgi-bin/webscr";
 }else{
    // for live mode
    $url="https://www.paypal.com/cgi-bin/webscr";
 }

 

// instead of use of fopen you can use
$res=file_get_contents($url."?".$req);
mail('naseema.n@armiasystems.com','IPN Result-RES', $res);
// compare response
if (strcmp (trim($res), "VERIFIED") == 0) {
 
	$cc_tran 	= $_POST['txn_id'];
	$cost		= $_POST['payment_gross'];
	$userId		= $_POST['custom'];
	$siteId		= $_POST['item_number'];

        mail('naseema.n@armiasystems.com','IPN Result-POST', $_POST);
	
	if($cc_tran != '' && $cost != '' && $userId != '' && $siteId != '') {
		
	
		$paymentStatus = getPaymentStatusByUser($siteId,$userId);
                
                mail('naseema.n@armiasystems.com','IPN Result-PAYMENT-STATUS', $paymentStatus);

	 	if($paymentStatus <= 0) {
        	$Cust_id=uniqid("Cust");

        	$insertPaymentData = "INSERT INTO tbl_payment(nsite_id,nuser_id,namount,ddate,vpayment_type,vtxn_id,vuniqid) Values(
                                '" . mysql_real_escape_string($siteId) . "',
                                '" . mysql_real_escape_string($userId) . "',
                                '" . $cost . "',
                                now(),
                                '" . mysql_real_escape_string('PayPal') . "',
                                '" . $cc_tran . "',
                                '" . $Cust_id . "')";
        	mysql_query($insertPaymentData) or die(mysql_error());

                mail('naseema.n@armiasystems.com','IPN Result-INSERT-DATA', $insertPaymentData);

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


}
else if (strcmp (trim($res), "INVALID") == 0) {
 
}


//Invoice Mail to Shop
function sendInvoiceMail($userDetails,$invoiceId,$amount,$transactionId=0){

    global $currencyArray;
    $siteName    = getSettingsValue('site_name');
    $userName    = $userDetails['vuser_name'].' '.$userDetails['vuser_lastname'];
    $currencyVal = getSettingsValue('currency');
    $currency    = $currencyArray[$currencyVal]['symbol'];
    $amount      = number_format($amount,2);

    $adminEmail  = getSettingsValue('admin_mail');
    $logo        = getSettingsValue('Logourl');
    $logoUrl     = BASE_URL.$logo;
    $invoiceDate = date("m/d/Y");
    $createdSite = $_SESSION['siteDetails']['siteInfo']['siteName'];

    $email      = ($userDetails['vuser_email'])?$userDetails['vuser_email']:'-';
    $phone      = ($userDetails['vuser_phone'])?$userDetails['vuser_phone']:'-';

     $mailContents = '<html><head></head>
                    <body>
                    <div style="width:180mm; margin:0 15mm;">
                     <p style="text-align:center; font-weight:bold; padding-top:5mm;">INVOICE</p> <br />
                     <table class="heading" style="width:100%;border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-spacing:0; border-collapse: collapse; height:50mm;">
                        <tr>
                            <td style="width:80mm;border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm;">
                                <h1 class="heading" style="font-size:14pt;color:#000;font-weight:normal;">'.$siteName.'</h1>
                                <h2 class="heading" style="font-size:9pt;color:#000;font-weight:normal;">
                                    <br /><img src="'.$logoUrl.'"><br /> <br />
                                    E-mail : '.$adminEmail.'<br />
                                </h2>
                            </td>
                            <td rowspan="2" valign="top" align="right" style="padding:3mm;border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;">
                                <table style="border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-spacing:0; border-collapse: collapse;">
                                    <tr><td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm;">Invoice No : </td><td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm;">'.$invoiceId.'</td></tr>
                                    <tr><td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm;">Dated : </td><td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm;">'.$invoiceDate.'</td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding: 2mm; color:#000;">
                                <b>Invoiced To :</b><br />
                                '.$userName.'<br />
                                '.$userDetails['vuser_address1'].'<br />
                                '.$userDetails['vcity'].'<br />
                                '.$userDetails['vstate'].'<br />
                                '.$userDetails['vzip'].'<br />
                                <br />
                                Email : '.$email.'<br />
                                Phone : '.$phone.'<br />
                            </td>
                        </tr>
                    </table>

            <div id="content">
                <div id="invoice_body" style="width:100%;">

                    <table style="width:100%;border-left: 1px solid #ccc;border-top: 1px solid #ccc;border-spacing:0;border-collapse: collapse;margin-top:5mm;">

                        <tr style="background:#eee;">
                            <td style="width:8%;border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding:2mm 0;text-align:center;font-size:9pt;"><b>#</b></td>
                            <td style="border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding:2mm 0;text-align:center;font-size:9pt;"><b>Created Site</b></td>
                            <td colspan="2" style="width:35%;border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;padding:2mm 0;text-align:center;font-size:9pt;"><b>Total</b></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">1</td>
                            <td style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">'.$createdSite.'</td>
                            <td colspan="2" style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">'.$currency.' '.$amount.'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;"></td>
                            <td style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;"></td>
                            <td colspan="2" style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;"></td>
                            <td style="border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;font-size: 9pt;padding: 2mm 0;text-align: center;">Total :</td>
                            <td class="mono" style="font-family: monospace;font-size: 10pt;padding-right: 3mm;text-align: right; border-bottom: 1px solid #CCCCCC;border-right: 1px solid #CCCCCC;">'.$currency.' '.$amount.'</td>
                        </tr>
                    </table>
                </div>
                <br />
                <hr style="color:#ccc;background:#ccc;" />
                <br />
            </div>
            <br />
        </div></body></html>';

    $subject  = "Invoice with ". $siteName . " for Created Site!";
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From: ".$adminEmail."<".$adminEmail.">" . "\r\n";
    $to       = $email;
    $mailsent = @mail($to, $subject, $mailContents, $headers);
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

function getPaymentStatusByUser($siteId,$userId) {

    if($siteId > 0 && $userId > 0 ) {
        $paymentStatusQuery = "SELECT count(npayment_id) as paymentCount FROM tbl_payment WHERE nsite_id=".$siteId." AND nuser_id=".$userId;
        $paymentStatusRes = mysql_query($paymentStatusQuery);
        $paymentStatusVal = mysql_fetch_assoc($paymentStatusRes);
        return $paymentStatusVal['paymentCount'];
    }
}

function getUserDetails($userId){
    $userQuery = "SELECT * FROM tbl_user_mast WHERE nuser_id=".$userId;
    $userRes   = mysql_query($userQuery);
    $userVal = mysql_fetch_assoc($userRes);
    return $userVal;
}
 
exit();






?>    