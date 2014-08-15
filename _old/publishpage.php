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

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/sitefunctions.php";
 global $currencyArray;
   $currency = getSettingsValue('currency');
//echopre($_SESSION);
//google checkout validation
if ($_GET['msg'] == "gc") {
	$tmpsiteid 		= $_GET['tid'];
	$userId 		= $_SESSION["session_userid"];
	$paymentStatus 	= getPaymentStatusByUser($tmpsiteid,$userId);
	if($paymentStatus > 0) {
    	$redirecturl = "downloadsite.php?sid=" . $tmpsiteid;
        header("Location:$redirecturl");
    }
}


// paypal verification
if ($_GET['msg'] == "s") {
	$tmpsiteid 		= $_GET['item_number'];
	$userId 		= $_SESSION["session_userid"];
	$paymentStatus 	= getPaymentStatusByUser($tmpsiteid,$userId);
	if($paymentStatus > 0) {
    	$redirecturl = "downloadsite.php?sid=" . $tmpsiteid;
        header("Location:$redirecturl");
    }
}



if (get_magic_quotes_gpc()) {
    $_POST 	= array_map('stripslashes_deep', $_POST);
    $_GET 	= array_map('stripslashes_deep', $_GET);
    $_COOKIE 	= array_map('stripslashes_deep', $_COOKIE);
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

//input variables
//$tmpsiteid = $_SESSION['session_currenttempsiteid'];
//$userid = $_SESSION["session_userid"];
$rootserver 	= ($_SESSION["session_rootserver"])?$_SESSION["session_rootserver"]:BASE_URL;
$secureserver	= ($_SESSION["session_secureserver"])?$_SESSION["session_secureserver"]:BASE_URL;

$message_err 	= "";
$redirecturl 	= "";
$tmpsiteid 	= ($_SESSION['siteDetails']["siteId"] != "")?$_SESSION['siteDetails']["siteId"]	:$_POST["tid"];
$template_type  = ($_GET["type"] != "")	?$_GET["type"]:$_POST["type"];
$userid 	= ($_SESSION["session_userid"] != "")?$_SESSION["session_userid"]:$_POST["uid"];
$style 		= ($_GET["style"] != "")?$_GET["style"]	:$_POST["style"];

$pm		= isset($_POST['pm'])		?	$_POST['pm']		:	"";
$msg            = isset($_REQUEST['msg'])	?	$_REQUEST['msg']	:	"";
$flag		= false;


// Get user details
$userData     = getUserDetails($userid); 
$firstName    = ($_POST['txtFirstName'])? $_POST['txtFirstName']:$userData['vuser_name'];
$lastName     = ($_POST['txtLastName'])? $_POST['txtFirstName']:$userData['vuser_lastname'];
$address      = ($_POST['txtAddress'])? $_POST['txtAddress']:$userData['vuser_address1'];
$city         = ($_POST['txtCity'])? $_POST['txtCity']:$userData['vcity'];
$state        = ($_POST['txtState'])? $_POST['txtState']:$userData['vstate'];
$country      = ($_POST['vcountry'])? $_POST['vcountry']:$userData['vcountry'];
$zip          = ($_POST['txtPostal'])? $_POST['txtPostal']:$userData['vzip'];
$zipLp        = ($_POST['txtZIP'])? $_POST['txtZIP']:$userData['vzip'];
$phone        = ($_POST['txtPhone'])? $_POST['txtPhone']:$userData['vuser_phone'];
$email        = ($_POST['txtEmail'])? $_POST['txtEmail']:$userData['vuser_email'];

if ($pm <> "" ) {
    $_SESSION['p_tid']	 = $tmpsiteid;
    $_SESSION['p_type']	 = $template_type;
    $_SESSION['p_uid']	 = $userid;
    $_SESSION['p_style'] = $style;
}

//below will work when coming back from payment gateways
if ($tmpsiteid == "" AND $template_type == "" AND $userid == "" AND $style == "") {
    $tmpsiteid 	    = $_SESSION['p_tid'];
    $template_type  = $_SESSION['p_type'];
    $userid 	    = $_SESSION['p_uid'];
    $style 	    = $_SESSION['p_style'];
}

if(empty($_SESSION["session_style"]) == true) {
    $_SESSION["session_style"] = $style;
}

//set amount for publishing
$qry	= "select vname,vvalue from tbl_lookup where vname IN('site_price','naff_amnt')";
$result	= mysql_query($qry);
while($row = mysql_fetch_array($result)) { 
    switch($row["vname"]) {
        case site_price:
            //$cost=(int)$row["vvalue"];
            $cost=$row["vvalue"];
            break;
        case naff_amnt:
            $var_aff_amnt = (int)$row["vvalue"];
            break;
    }
} 

//looking for availabel payment methods
$sql	= "SELECT * FROM `tbl_lookup`";
$res	= mysql_query($sql);
while($row = mysql_fetch_array($res)) {
    switch($row["vname"]) {
        case enable_google:
            $enable_google	= $row["vvalue"];
            break;
        case google_demo:
            $google_demo	= $row["vvalue"];
            break;
        case google_id:
            $google_id	= $row["vvalue"];
            break;
        case google_key:
            $google_key	= $row["vvalue"];
            break;
        case enable_paypal:
            $enable_paypal	= $row["vvalue"];
            break;
        case paypal_sandbox:
            $paypal_sandbox = $row["vvalue"];
            break;
        case paypal_email:
            $paypal_email = $row["vvalue"];
            break;
        case enable_gateways:
            $enable_gateways = $row["vvalue"];
            break;
        case checkout_demo:
            $checkout_demo = $row["vvalue"];
            break;
        case checkout_key:
            $checkout_key = $row["vvalue"];
            break;
        case checkout_productid:
            $checkout_productid = $row["vvalue"];
            break;
        case  auth_demo:
            $auth_demo = $row["vvalue"];
            break;
        case auth_pass:
            $auth_pass = $row["vvalue"];
            break;
        case auth_email:
            $auth_email = $row["vvalue"];
            break;
        case auth_txnkey:
            $auth_txnkey = $row["vvalue"];
            break;
        case auth_loginid:
            $auth_loginid = $row["vvalue"];
            break;
        case paymentsupport:
            $paymentsupport = $row["vvalue"];
            break;
        case admin_mail:
            $admin_mail = $row["vvalue"];
            break;
        case paypal_token:
            $paypal_token = $row["vvalue"];
            break;
    }
}

//if ($enable_google == "YES") {
require_once('gc/library/googlecart.php');
require_once('gc/library/googleitem.php');
require_once('gc/library/googleshipping.php');
require_once('gc/library/googletax.php');
require_once('gc/library/googleresponse.php');
require_once('gc/library/googlemerchantcalculations.php');
require_once('gc/library/googleresult.php');
//}


if($paypal_sandbox == "YES") {
    $paypalurl 		= "https://www.sandbox.paypal.com/cgi-bin/webscr";
    $paypalurlNew       = "www.sandbox.paypal.com";
    $paypalbuttonurl 	= "https://www.sandbox.paypal.com/en_US/i/btn/x-click-but23.gif";
    $paypalfsockopen 	= "www.sandbox.paypal.com";
    $paypalfsockopenssl = "ssl://www.sandbox.paypal.com";
} else {
    $paypalurl 		= "https://www.paypal.com/cgi-bin/webscr";
    $paypalurlNew       = "www.paypal.com";
    $paypalbuttonurl 	= "https://www.paypal.com/en_US/i/btn/x-click-but23.gif" ;
    $paypalfsockopen 	= "www.paypal.com";
    $paypalfsockopenssl = "ssl://www.paypal.com";
}

//payment flag = default to false
$bool_payment	=	false;


//check for payment data for the site id in payment table
// if true then message is shown saying 'payment over for site'
$sql = "Select * from tbl_payment where nsite_id='" . $tmpsiteid . "'";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
    $bool_payment	= true;
} else {
    $sql = "select * from tbl_site_mast where nsite_id='".addslashes($tmpsiteid)."'";
    $result = mysql_query($sql);
    if(mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        if ($row["nuser_id"] != $userid) {
            $message_err=VAL_INPUT;

        } elseif ($_POST["postbackCC"] == "P") {
            $FirstName 	= $_POST["txtFirstName"];
            $LastName 	= $_POST["txtLastName"];
            $Address 	= $_POST["txtAddress"];
            $City 	= $_POST["txtCity"];
            $State	= $_POST["txtState"];
            $Zip	= $_POST["txtPostal"];
            $CardNum	= $_POST["txtccno"];
            $Email	= $_POST["txtEmail"];
            $CardCode	= $_POST["txtcvv2"];
            $Country	= $_POST["vcountry"];
            $Month 	= $_POST["txtMM"];
            $Year 	= $_POST["txtYY"];

            //Section WFM
            //Uncomment WFM section for Wells Fargo merchant section
            $Cust_ip	= getClientIP();
            $Company	= $_POST["txtCompany"];
            $Phone	= $_POST["txtPhone"];
            $Cust_id	= uniqid("Cust");
            //END WFM

            $cc_tran	= "";
            $cc_flag 	= false;

            require("credit_inte.php"); // if you do not have cURL support comment this line and uncomment the below section
            if(($cc_flag == true) ) { 
                $arrRes      = process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id, "CreditCard");
                $redirecturl = $arrRes['url'];
                $var_id      = $arrRes['var'];  
            } else {
                $message_err = VAL_CREDITCARD;
                //   $_SESSION['session_paymentmode']="failure";
            }
            $flag	= true;

        } elseif ($_POST["postbackCC"] == "LP") {

            $txtFirstName 	= $_POST["txtFirstName"];
            $txtLastName 	= $_POST["txtLastName"];
            $txtAddress 	= $_POST["txtAddress"];
            $txtCity 		= $_POST["txtCity"];
            $txtState		= $_POST["txtState"];
            $txtZIP		= $_POST["txtZIP"];
            $txtCCNumber	= $_POST["txtCCNumber"];
            $txtEmail		= $_POST["txtEmail"];
            $txtCode		= $_POST["txtCode"];
            $ddlCountry		= $_POST["ddlCountry"];
            $txtMM 		= $_POST["txtMM"];
            $txtYY 		= $_POST["txtYY"];

            //Section WFM
            //Uncomment WFM section for Wells Fargo merchant section
            $Cust_ip	= getClientIP();
            $txtCompany	= $_POST["txtCompany"];
            $txtPhone	= $_POST["txtPhone"];
            $Cust_id	= uniqid("Cust");
            //END WFM

            $cc_tran	= "";
            $cc_flag 	= false;

            require("yourpay.php"); // if you do not have cURL support comment this line and uncomment the below section
            if(($cc_flag == true) ) {
                $arrRes	= process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id, "LinkPoint");

                $redirecturl	= $arrRes['url'];
                $var_id		= $arrRes['var'];
            } else {
                $message_err = VAL_CREDITCARD;
                //   $_SESSION['session_paymentmode']="failure";
            }
            $flag	= true;

        } else { //} else if ($pos = strpos($referrer, "paypal.com")) {

            if ($msg == "s") {      
                // successful paypal payment

                // read the post from PayPal system and add 'cmd'
                $req 		= 'cmd=_notify-synch';
                $tx_token 	= $_GET['tx'];

                $cc_tran	= $tx_token; // for our code

                $auth_token = $paypal_token; // PDT Key
                $req 		.= "&tx=$tx_token&at=$auth_token"; 

                // post back to PayPal system to validate
                //$header 	.= "POST /cgi-bin/webscr HTTP/1.0\r\n";
                $header         .= "POST https://".$paypalurlNew."/cgi-bin/webscr HTTP/1.0\r\n";
                $header 	.= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header         .= "Host: ".$paypalurlNew."\r\n";
                $header 	.= "Content-Length: " . strlen($req) . "\r\n";
                $header         .= "Connection: close\r\n\r\n";

                // If success, get payment data from tbl_payment  - Paypal through ipn
                $siteId = $_SESSION['siteDetails']['siteId'];
                $sqlPaymentData = "SELECT * FROM tbl_payment WHERE nsite_id='".$siteId."' AND nuser_id='".$userid."' AND vtxn_id='".$tx_token."'";
                $resPaymentData = mysql_query($sqlPaymentData) or die(mysql_error());
                $valPaymentData = mysql_fetch_assoc($resPaymentData);

                if($paypal_sandbox == "YES" && ($_GET['st']=='Completed' || $_GET['st']=='Pending') ) {
                        $cc_flag		= true;
                        $tmpsiteid		= $_SESSION['p_tid'];
                        $template_type          = $_SESSION['p_type'];
                        $userid			= $_SESSION['p_uid'];
                        $style			= $_SESSION['p_style'];
                        $Cust_id		= uniqid("Cust");
                        /// publishing the site

                        //$arrRes	= process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id);
                        //$arrRes	= process_after_payment_paypal($tmpsiteid, $userid, $cost, $var_aff_amnt, $cc_tran,$paymentId);
                        $redirecturl = "downloadsite.php?sid=" . $siteId;
                        //$var_id		= $arrRes['var'];

                        header("Location:$redirecturl");
                }

                if(mysql_num_rows($resPaymentData) > 0 ){

                    $paymentId = $valPaymentData['npayment_id'];
                    
                    //$arrRes	= process_after_payment_paypal($tmpsiteid, $userid, $cost, $var_aff_amnt, $cc_tran,$paymentId);
                    $redirecturl = "downloadsite.php?sid=" . $siteId;
                    //$var_id		= $arrRes['var'];

                    header("Location:$redirecturl");
                }else{
                    $message_err = VAL_PAYMENT;
                }
                $flag	= true;
                // If success, get payment data from tbl_payment

                //$fp 		= fsockopen ($paypalfsockopen, 80, $errno, $errstr, 30);
                // If possible, securely post back to paypal using HTTPS
                // Your PHP server will need to be SSL enabled

                /*
                $fp = fsockopen ($paypalfsockopenssl, 443, $errno, $errstr, 30);

                if (!$fp) { 
                    // HTTP ERROR
                    //$message_err = "Invalid payment.";
                } else {  
                    fputs ($fp, $header . $req);
                    // read the body data
                    $res = '';
                    $headerdone = false;
                    while (!feof($fp)) {
                        $line = fgets ($fp, 1024);
                        if (strcmp($line, "\r\n") == 0) {
                            // read the header
                            $headerdone = true;
                        } else if ($headerdone) {
                            // header has been read. now read the contents
                            $res .= $line;
                        }
                    }
 
                    
                    // parse the data
                    $lines = explode("\n", $res); 
                    $keyarray = array();
                    if (strcmp ($lines[0], "SUCCESS") == 0) {
                        for ($i=1; $i<count($lines);$i++) {
                            list($key,$val) = explode("=", $lines[$i]);
                            $keyarray[urldecode($key)] = urldecode($val);
                        }
                        // check the payment_status is Completed
                        // check that txn_id has not been previously processed
                        // check that receiver_email is your Primary PayPal email
                        // check that payment_amount/payment_currency are correct
                        // process payment
                        $firstname 	= $keyarray['first_name'];
                        $lastname 	= $keyarray['last_name'];
                        $itemname 	= $keyarray['item_name'];
                        $amount 	= $keyarray['mc_gross'];

                        $cc_flag		= true;
                        $tmpsiteid		= $_SESSION['p_tid'];
                        $template_type	= $_SESSION['p_type'];
                        $userid			= $_SESSION['p_uid'];
                        $style			= $_SESSION['p_style'];
                        $Cust_id		= uniqid("Cust");
                        /// publishing the site

                        $arrRes	= process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id);
                        $redirecturl	= $arrRes['url']; 
                        $var_id		= $arrRes['var'];

                        header("Location:$redirecturl"); 

                    } else if (strcmp ($lines[0], "FAIL") == 0) {
                        // log for manual investigation
                        $message_err = VAL_PAYMENT;

                    }
                    $flag	= true;
                }
                fclose ($fp);  */

            } else if ($msg == "f") {
                // failure of paypal payment
                $flag	= true;
                $message_err = VAL_PAYMENT;

            } else if ($msg == "cs") {

                if (isset($_POST['credit_card_processed']) AND isset($_POST['key']) AND isset($_POST['sid']) AND $_POST['sid'] == $checkout_key) {
                    if (isset($_REQUEST['credit_card_processed']) AND $_REQUEST['credit_card_processed'] == "Y") {
                        $cc_flag		= true;
                        $cc_tran		= $_REQUEST['order_number'];
                        $keys			= $_REQUEST['key'];
                        $tmpsiteid		= $_SESSION['p_tid'];
                        $template_type	= $_SESSION['p_type'];
                        $userid			= $_SESSION['p_uid'];
                        $style			= $_SESSION['p_style'];
                        $Cust_id		= uniqid("Cust");
                        $arrRes			= process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id, "2CheckOut");
                        $redirecturl	= $arrRes['url'];
                        $var_id			= $arrRes['var'];
                    } else {
                        // failure of 2checkout payment
                        $message_err = VAL_PAYMENT;
                    }
                } else {
                    // invalid referer
                    $message_err = VAL_PAYMENT;
                }
                $flag	= true;

            } else if ($msg == "cf") {
                // failure of 2checkout payment
                $flag		 = true;
                $message_err = VAL_PAYMENT;
            } else if ($msg == "gc") {
                // google checkout - need to confirm the payment process
                //$arrRes		= process_after_payment($rootserver, $template_type, $tmpsiteid, $userid,$cost, $var_aff_amnt, $cc_tran, $Cust_id, "GoogleCheckout");
                //$redirecturl	= $arrRes['url'];
              $redirecturl = "downloadsite.php?sid=" . $tmpsiteid;
                header("Location:$redirecturl");
            }
        }

    } else {
        $message_err = VAL_INPUT;
    }
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

function process_after_payment($rootserver, $template_type, $tmpsiteid, $userid, $cost, $var_aff_amnt, $cc_tran, $Cust_id, $type="PayPal") {
    
    // Save payment details
    $paymentData = savePaymentDetails($type,$cost,$cc_tran);

    $userId      = $_SESSION['session_userid'];
    $userDetails = getUserDetails($userId);
    
    // Send Invoice Mail
    sendInvoiceMail($userDetails,$paymentData,$cost,$cc_tran);
    
    //pay affiliate
     payaffiliate($userid,$var_aff_amnt);
     $redirecturl = "downloadsite.php?sid=" . $tmpsiteid;
    
    return array("url" => $redirecturl, "var" => $tmpsiteid);
}

function process_after_payment_paypal( $tmpsiteid, $userid, $cost, $var_aff_amnt, $cc_tran,$paymentId) {

    $userId      = $_SESSION['session_userid'];
    $userDetails = getUserDetails($userId);

    // Send Invoice Mail
    //sendInvoiceMail($userDetails,$paymentId,$cost,$cc_tran);

    //pay affiliate
     //payaffiliate($userid,$var_aff_amnt);
     $redirecturl = "downloadsite.php?sid=" . $tmpsiteid;

    return array("url" => $redirecturl, "var" => $tmpsiteid);
}

function UseCase1() {

    global $google_id;
    global $google_key;
    global $google_demo;
    global $cost;
    global $tmpsiteid;
    global $userid;
    global $style;
    global $template_type;

    if ($google_demo == "YES")
        $server_type	= "sandbox";
    else
        $server_type	= "checkout";
// Create a new shopping cart object
//$merchant_id = "";  // Your Merchant ID
//$merchant_key = "";  // Your Merchant Key
//$server_type = "sandbox";

        
          $currencyVal = getSettingsValue('currency');
    $currency = $currencyVal;
    $cart = new GoogleCart($google_id, $google_key, $server_type, $currency);

// Add items to the cart
    $item_1 = new GoogleItem("EasyCreate", // Item name
            "Website Builder", // Item description
            1, // Quantity
            $cost); // Unit price

//$item_1->SetMerchantPrivateItemData("<tid>$tmpsiteid</tid><uid>$userid</uid><style>$style</style><type>$template_type</type>");
//$item_1->SetMerchantItemId("Item#012345");   
    $cart->AddItem($item_1);

// Add <merchant-private-data>
 $cart->SetMerchantPrivateData("$tmpsiteid:$userid");
//$cart->SetContinueShoppingUrl("http://192.168.0.11/girish/ecc/publishpage.php?msg=gc");
    $cart->SetContinueShoppingUrl(BASE_URL."publishpage.php?msg=gc&tid=$tmpsiteid");

    $cart->AddRoundingPolicy("HALF_UP", "PER_LINE");

// Display XML data
// echo "<pre>";
// echo htmlentities($cart->GetXML());
// echo "</pre>";

// Display Google Checkout button
    echo $cart->CheckoutButtonCode("LARGE");
}

include "includes/userheader.php";

?>
<script>
    <!--
    function checkMail(email)
    {
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
            eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
            eFlag = false;
        }
        else
        {
            var dot=arr[1].split('.');
            if(dot.length < 2)
            {
                eFlag = false;
            }
            else
            {
                if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                {
                    eFlag = false;
                }

                for(i=1;i < dot.length;i++)
                {
                    if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4)
                    {
                        eFlag = false;
                    }
                }
            }
        }
        return eFlag;
    }
    function onlyNumeric(){
        if (event.keyCode<48||event.keyCode>57)
            return false;
    }
    function clickPay() {
        var frm = document.PaymentForm;

        if (frm.txtFirstName.value.length == 0 ) {
            alert('<?php echo VAL_FNAME;?>');
            return false;
        }
        if (frm.txtccno.value.length == 0 ) {
            alert('<?php echo VAL_CARD_NO;?>');
            return false;
        }
        if (frm.txtcvv2.value.length == 0) {
            alert('<?php echo VAL_CODE;?>');
            return false;
        }
        if (frm.txtMM.value.length == 0 || frm.txtYY.value.length == 0) {
            alert('<?php echo VAL_EXPIRY;?>');
            return false;
        }
        if(frm.txtEmail.value.length == 0 || !checkMail(frm.txtEmail.value)) {
            alert('<?php echo VAL_EMAIL;?>');
            return false;
        }
        frm.postbackCC.value="P";
        frm.method="post";
        frm.action="publishpage.php";
        frm.submit();
    }

    function clickPayLP()
    {
        var frm = document.PaymentFormLP;

        if (frm.txtFirstName.value.length == 0 ) {
            alert('<?php echo VAL_FNAME;?>');
            return false;
        }
        
        if (frm.txtCCNumber.value.length == 0) {
            alert('<?php echo VAL_CARD_NO;?>');
            return false;
        }
        if (frm.txtCode.value.length == 0 ) {
             alert('<?php echo VAL_CODE;?>');
            return false;
        }
        if (frm.txtMM.value.length == 0 || frm.txtYY.value.length == 0) {
            alert('<?php echo VAL_EXPIRY;?>');
            return false;
        }
        
        if(frm.txtEmail.value.length == 0 || !checkMail(frm.txtEmail.value)) {
            alert('<?php echo VAL_EMAIL;?>');
            return false;
        }
        frm.postbackCC.value="LP";
        frm.method="post";
        frm.action="publishpage.php";
        frm.submit();
    }

    function checkNumber(t) {
        if(t.value.length == 0 ||  isNaN(t.value) || t.value.substr(0,1) == " " || parseInt(t.value) < 0) {
            t.value="";
        }
    }
    function submitUrl(url,id) {
        var frm = document.PaymentForm;
        frm.method="post";
        frm.sid.value=id;
        frm.tid.value="";
        frm.style.value="";
        frm.uid.value="";
        frm.type.value="";
        frm.action=url;
        frm.submit();
    }
    function submitUrl1(url,id) {
        var frm = document.PaymentForm1;
        frm.method="post";
        frm.sid.value=id;
        frm.tid.value="";
        frm.style.value="";
        frm.uid.value="";
        frm.type.value="";
        frm.action=url;
        frm.submit();
    }

    -->
</script>

<?php
if($cc_flag == true || $bool_payment == true) $pageHeading = ' Payment Success'; else  $pageHeading='Make Payment';
?>
 <h2><span class="step-cnt">5</span><?php echo $pageHeading;?></h2>
 <div style="float: right;"><a class="link01" href="editor.php"><?php echo BACK_EDITOR;?></a></div>

<?php if($flag) {
    ?>
<style type="text/css">
    <!--
    .style122 {color: #FF0000}
    -->
</style>

 <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
     <tr>
         <td align="center" class=maintext>
             <!--div class="pymnt_mainmsg">
                 <table width="100%">
                     <tr>
                         <td colspan="3">&nbsp;</td>
                     </tr>
                     <tr>
                         <td colspan="3" align="center"><p> <?php //echo PAYMENT_NOTE?></p> <br>
                         </td>
                     </tr>
                     <tr>
                         <td width="47%" align="right"><p><span>Amount :</span></p></td>
                         <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span>$<?php //echo $cost;?></span></p></td>
                     </tr>
                     <tr>
                         <td colspan="3">&nbsp;</td>
                     </tr>
                 </table>
             </div-->
         </td>
     </tr>
     <tr>
         <td class=maintext>&nbsp;</td>
     </tr>
     <tr>
         <td align="center" valign="top" class="maintext">
             <p align="center" class="maintext">
                 <?php
                 if($cc_flag == true) {
                     header("Location:$redirecturl");
                     echo("<div class=\"pymnt_mainmsg\"><table><tr><td style=\"font-size:14px;\" colspan=\"2\" align=\"center\" class=\"maintext\"><br>&nbsp;<br>&nbsp;<b>Payment process completed. <br><br><a href=\"#\" onclick='submitUrl1(\"$redirecturl\",$var_id);'>Click here</a> to proceed further.</b><br>&nbsp;<br>&nbsp;</td></tr></table></div>");
                 } else {
                     if(!empty($message_err)) {
                         echo("<tr><td style=\"font-size:14px;\" colspan=\"2\" align=\"center\"  class=\"maintext\"><b> $message_err<br>$cc_err<br>&nbsp;</b></td></tr>");
                         ?>
                    <tr><td align="right"><a class="link01" href="publishpage.php"><?php echo BACK_PAYMENT;?></a></td></tr>
             <?php
         }
     }
     ?>
 </p>
</td>
</tr>
<tr>
    <td>&nbsp;
        <form name="PaymentForm1" method="post" action="">
            <?php
            echo("<input type=\"hidden\" name=\"tid\" id=\"tid\" value=\"$tmpsiteid\">
		<input type=\"hidden\" name=\"type\" id=\"type\" value=\"$template_type\">
		<input type=\"hidden\" name=\"uid\" id=\"uid\" value=\"$userid\">
		<input type=\"hidden\" name=\"style\" id=\"style\" value=\"$style\">
		<input type=\"hidden\" name=\"sid\" id=\"sid\" value=\"\">");
            ?>
        </form>
    </td>
</tr>
</table>
    <?php } ?>

<?php if ($pm == "" AND !$flag) { 
 
	?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
    <tr>
        <td align="center" class=maintext><table width="100%">
                <tr>
                    <td align="center">
                        <div class="pymnt_mainmsg">
                            <table width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE;?></p> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            </table>
                        </div>
					</td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td class=maintext>&nbsp;

        </td>
    </tr>
    <tr>
        <td align="center" valign="top" class="maintext">
                <?php
                if ($paymentsupport == "yes") {
                    if($enable_google == "YES") {?>
            <form method="post" name="frmPays" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input name="imageField" type="image" title="Make payments with Google Checkout - it's fast, free and secure! " src="images/checkout.gif" width="180" height="46" border="0" >
                <input type="hidden" name="tid" id="tid" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $template_type; ?>">
                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="style" id="style" value="<?php echo $style; ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="pm" id="pm" value="GC">
            </form>
                        <?php }
                    echo "&nbsp;&nbsp;&nbsp;";
                    if($enable_paypal == "YES") { ?>
            <form method="post" name="frmPays" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input name="imageField" type="image" src="images/pp.jpg" title="Make payments with PayPal - it's fast, free and secure! " border="0" >
                <input type="hidden" name="tid" id="tid" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $template_type; ?>">
                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="style" id="style" value="<?php echo $style; ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="pm" id="pm" value="PP">
            </form>
            <?php }
            echo "&nbsp;&nbsp;&nbsp;";
            if($enable_gateways == "CO") { ?>
            <form method="post" name="frmPays" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input name="imageField" type="image" src="images/2co.jpg" valign="top" title="Pay with 2Checkout.com" border="0" >
                <input type="hidden" name="tid" id="tid" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $template_type; ?>">
                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="style" id="style" value="<?php echo $style; ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="pm" id="pm" value="CO">
                <br>
                <br>
                <span class="style122">*<?php echo PUBLISH_NOTE;?></span> <?php echo PUBLISH_WARNING;?>
            </form>
            <?php } else if($enable_gateways == "AN") {?>
            <form method="post" name="frmPays" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <input name="imageField" type="image" src="images/cc.gif" valign="top" title="Pay with Credit Card" border="0" >
                <input type="hidden" name="tid" id="tid" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $template_type; ?>">
                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="style" id="style" value="<?php echo $style; ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="pm" id="pm" value="AN">
            </form>
            <?php } else if($enable_gateways == "LP") {?>
            <form method="post" name="frmPays" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input name="imageField" type="image" src="images/linkpoint.gif" valign="top" title="Pay with Credit Card" border="0" >
                <input type="hidden" name="tid" id="tid" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $template_type; ?>">
                <input type="hidden" name="uid" id="uid" value="<?php echo $userid; ?>">
                <input type="hidden" name="style" id="style" value="<?php echo $style; ?>">
                <input type="hidden" name="sid" id="sid" value="">
                <input type="hidden" name="pm" id="pm" value="LP">
            </form>
                        <?php }
                }

                if(($enable_paypal !="YES") and ($enable_gateways != "CO") and ($enable_gateways != "AN") OR $paymentsupport == "no") {?>
							<?php echo PUBLISH_WARNING2;?> <a href="mailto:<?php echo $admin_mail;?>"><?php echo PUBLISH_WARNING3;?></a>
                    <?php } ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
    <?php } elseif ($pm == "CO") { ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td align="center" class=maintext><div class="pymnt_mainmsg">
					<table width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE;?></p> <br>
                                        </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            </table>
					</div></td>
    </tr>
    <tr>
        <td class=maintext>&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top" class="maintext">
            <p align="center" class="maintext"><br><br><br>
                <b><?php echo PUBLISH_WAITING_INTERFACE;?></b><br><br><br>
            </p>
            <form action="https://www.2checkout.com/2co/buyer/purchase" method="post" name="frmPayment">
                   <?php if ($checkout_demo == "YES") { ?>
                <input type="hidden" name="demo" value="Y">
                        <?php } ?>
                <input type="hidden" name="return_url" value="<?php echo $secureserver ."/publishpage.php?msg=cs"; ?>">
                <input type="hidden" name="fixed" value="Y">
                <input type="hidden" name="sid" value="<?php echo $checkout_key; ?>">
                <input type="hidden" name="total" value="<?php echo round($cost, 2); ?>">
                <input type="hidden" name="product_id"  value="<?php echo $checkout_productid; ?>">
                <input type="hidden" name="quantity"  value="1">
                <input type="hidden" name="pay_method" value="CC">
                <!--<input type="hidden" name="cart_order_id"  value="123easy456create89">-->
            </form>
               <?php echo "<script language='javascript'> document.frmPayment.submit();</script>";?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
    <?php } elseif ($pm == "PP") { ?>

<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
    <tr>
        <td align="center" class=maintext><div class="pymnt_mainmsg">
					<table width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE?></p> <br>
                                        </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            </table>
					</div></td>
    </tr>
    <tr>
        <td class=maintext>&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top" class="maintext">
            <p align="center" class="maintext"><br><br><br>
                <b><?php echo PUBLISH_WAITING_INTERFACE;?></b><br><br><br>
                <span class="style122">*<?php echo PUBLISH_NOTE;?></span> <?php echo PUBLISH_WARNING;?>	</p>
            <form action="<?php echo $paypalurl;?>" method="post" name="frmPayment">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
                <input type="hidden" name="item_name" value="EasyCreate">
                <input type="hidden" name="item_number" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="amount" value="<?php echo round($cost, 2); ?>">
                <input type="hidden" name="no_shipping" value="1">
                <!--<input type="hidden" name="notify_url" value="<?php echo $secureserver ."/publishpage.php?msg=n&tid="?><?php echo $tmpsiteid; ?>&uid=<?php echo $userid; ?>&style=<?php echo $style; ?>&type=<?php echo $template_type; ?>; ?>"> -->
                <input type="hidden" name="return" value="<?php echo $secureserver ."/publishpage.php?msg=s"; ?>">
                <input type="hidden" name="cancel_return" value="<?php echo $secureserver ."/publishpage.php?msg=f"; ?>">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="currency_code" value="<?php echo getSettingsValue('currency');?>">
                <input type="hidden" name="rm" value="2">
                <input type="hidden" name="bn" value="armiasystems_shoppingcart_wps_us">
                <input type="hidden" name="custom" id="custom" value="<?php echo $userid; ?>">
                <!--
                <input type="hidden" name="on0" id="on0" value="ipn_tid">
                <input type="hidden" name="on1" id="on1" value="ipn_type">
                <input type="hidden" name="on2" id="on2" value="ipn_uid">
                <input type="hidden" name="on3" id="on3" value="ipn_style">
                <input type="hidden" name="os0" id="os0" value="<?php echo $tmpsiteid; ?>">
                <input type="hidden" name="os1" id="os1" value="<?php echo $template_type; ?>">
                <input type="hidden" name="os2" id="os2" value="<?php echo $userid; ?>">
                <input type="hidden" name="os3" id="os3" value="<?php echo $style; ?>">
		-->
                <input type="image" src="<?php echo $paypalbuttonurl; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
            </form>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
    <?php } elseif ($pm == "GC") { ?>

<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
    <tr>
        <td align="center" class=maintext><div class="pymnt_mainmsg">
					<table width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE?></p> <br>
                                        </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            </table>
					</div></td>
    </tr>
    <tr>
        <td class=maintext>&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top" class="maintext">
            <p align="center" class="maintext"><br><br>
		<?php echo PUBLISH_CHECKOUT;?> <br>
                <br>
                <b><?php echo PUBLISH_WAITING_INTERFACE;?></b><br><br><br>
                    <?php UseCase1(); ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
    <?php } elseif ($pm == "AN" ) { ?>
<form name="PaymentForm" method="post" action="">
        <?php
        echo("<input type=\"hidden\" name=\"tid\" id=\"tid\" value=\"$tmpsiteid\">
<input type=\"hidden\" name=\"type\" id=\"type\" value=\"$template_type\">
<input type=\"hidden\" name=\"uid\" id=\"uid\" value=\"$userid\">
<input type=\"hidden\" name=\"style\" id=\"style\" value=\"$style\">
<input type=\"hidden\" name=\"sid\" id=\"sid\" value=\"\">");
        ?>
    <table width="100%" class="pymnttable1">
            <?php
            if($cc_flag == true) {
                header("Location:$redirecturl");
                echo("<tr><td style=\"font-size:14px;\" colspan=\"2\" align=\"center\" class=\"maintext\"><br>&nbsp;<br>&nbsp;<b>Payment process completed. <br><a href=\"#\" onclick='submitUrl(\"$redirecturl\",$var_id);'>Click here</a> to proceed further.</b><br>&nbsp;<br>&nbsp;</td></tr>");
            }
            else {
                if($bool_payment == true) {
                    echo("<tr><td style=\"font-size:14px;\" colspan=\"2\" align=\"center\"  class=\"maintext\"><b><br>&nbsp;<br>&nbsp;Payment for this site has already been done.<br><a href=\"$redirecturl\">Click here</a> to proceed further.<br>&nbsp;<br>&nbsp;</b></td></tr>");
                }
                else {

                    //  if($cc_flag == false && $_POST["postbackCC"] == "P") {
                    if(!empty($message_err)) {
                        echo("<tr><td colspan=\"2\" align=\"center\"  class=\"maintext\"><b> $message_err<br>$cc_err<br>&nbsp;</b></td></tr>");
                    }
                    ?>
        <tr>
            <td colspan="3" align="center" class="maintext">
                <div class="pymnt_mainmsg">
					<table width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE?></p> <br>
                                  </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                  </table>
			  </div>
            </td>
        </tr>
        <tr>
            <td align="left" class="maintext">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="80%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_FNAME;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left">
                <input type="text" name="txtFirstName" id="txtFirstName" value="<?php echo($firstName); ?>" size="30" maxlength="50" class="textbox">
            </td>
            <td width="60%" align="left">        &nbsp;
                <input type="hidden" name="postbackCC" id="postbackCC" value="">
            </td>
        </tr>
        <tr>
            <td width="80%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_LNAME;?>
            </td>
            <td width="60%" align="left" ><input type="text" name="txtLastName" id="txtLastName" value="<?php echo($lastName); ?>" size="30" maxlength="50" class="textbox"></td>
            <td width="60%" align="left" >&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_CNO;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left">
			<input type=text name="txtccno" class="textbox medium" id="txtccno" size="30" maxlength="16" onBlur="javascript:checkNumber(this);">
			<img src="images/visa_amex.gif" align="absmiddle"></td>
            <td width="60%" align="left">
                
            </td>
        </tr>
        <tr>
            <td width="80%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_CODE;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtcvv2" class="textbox" id="txtcvv2" size=14 maxlength="4" onBlur="javascript:checkNumber(this);"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_YEAR;?> <span style="color:red">*</span> <br>
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_YEAR_EG;?> 
            </td>
            <td width="60%" align="left"><input type=text name="txtMM"  class="textbox small" id="txtMM" size=3 maxlength="2" onBlur="javascript:checkNumber(this);">
                /
                <input type=text name="txtYY" class="textbox small" id="txtYY"  size=6 maxlength="4" onBlur="javascript:checkNumber(this);"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>

        <tr align="left">
            <td colspan=3  class="maintext">
                <h3 class="hding_11"><?php echo BILLING_ADDRESS;?> </h3>
          </td>
        </tr>
       
        <tr>
            <td  align="left" class="maintext"> &nbsp;&nbsp;<?php echo PUBLISH_COMPANY;?> <span style="color:red">*</span> </td>
            <td align="left"><input type=text name="txtCompany" class="textbox" id="txtCompany" size=30 maxlength=50 value="<?php echo($_POST["txtCompany"]); ?>"></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="80%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_ADDRESS;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtAddress" class="textbox" id="txtAddress" size=30 maxlength=60 value="<?php echo($address); ?>"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%"  align="left"  height="20" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_CITY;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtCity" class="textbox" id="txtCity" size=30 maxlength="40" value="<?php echo($city); ?>"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_STATE;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtState" class="textbox" id="txtState" size=30 maxlength=40 value="<?php echo($state); ?>"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td class="maintext" align="left"> &nbsp;&nbsp;<?php echo PUBLISH_PH;?> <span style="color:red">*</span> </td>
            <td align="left"><input type=text name="txtPhone" class="textbox" id="txtPhone" size=30 maxlength=25 value="<?php echo($phone); ?>"></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="80%" class="maintext" align="left">
                &nbsp;&nbsp;<?php echo PUBLISH_POSTAL;?> <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtPostal" class="textbox" id="txtPostal" size=30 maxlength=20  value="<?php echo($zip); ?>"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_COUNTRY;?> <span style="color:red">*</span>
            </td>
            <td>
                <select name="vcountry" id="vcountry" class="selectbox">
                    <?php
                    $country = $country;
                    include "includes/countries.php";
                    ?>
                </select>
            </td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="80%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_EMAIL;?>  <span style="color:red">*</span>
            </td>
            <td width="60%" align="left"><input type=text name="txtEmail" class="textbox" id="txtEmail" size=30 maxlength=255 value="<?php echo($email); ?>"></td>
            <td width="60%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="left">
                <input type="button" name="btPay" id="btPay" class="btn01 leftmr01"  value="Pay Now" onClick="javascript:clickPay();" >
            </td>
            <td>&nbsp;</td>
        </tr>
                    <?php
                }
            }
            ?>
    </table>
</form>
    <?php } elseif ($pm == "LP" ) { ?>
<form name="PaymentFormLP" method="post" action="">
        <?php
        echo("<input type=\"hidden\" name=\"tid\" id=\"tid\" value=\"$tmpsiteid\">
<input type=\"hidden\" name=\"type\" id=\"type\" value=\"$template_type\">
<input type=\"hidden\" name=\"uid\" id=\"uid\" value=\"$userid\">
<input type=\"hidden\" name=\"style\" id=\"style\" value=\"$style\">
<input type=\"hidden\" name=\"sid\" id=\"sid\" value=\"\">");
        ?>
		<br>
    <table width="100%" class="pymnttable1">
            <?php
            if($cc_flag == true) {
                header("Location:$redirecturl");
                echo("<tr><td colspan=\"2\" align=\"center\" class=\"maintext\"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<b>Payment process completed. <br><a href=\"#\" onclick='submitUrl(\"$redirecturl\",$var_id);'>Click here</a> to proceed further.</b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>");
            }
            else {
                if($bool_payment == true) {
                    echo("<tr><td colspan=\"2\" align=\"center\"  class=\"maintext\"><b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;Payment for this site has already been done.<br><a href=\"$rootserver/usermain.php\">Click here</a> to proceed further.<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</b></td></tr>");
                }
                else {

                    //  if($cc_flag == false && $_POST["postbackCC"] == "P") {
                    if(!empty($message_err)) {
                        echo("<tr><td colspan=\"2\" align=\"center\"  class=\"maintext\"><b> $message_err<br>$cc_err<br>&nbsp;</b></td></tr>");
                    }
                    ?>
        <tr>
            <td colspan="3" align="center" class="maintext">
                <div class="pymnt_mainmsg">
					<table width="100%" >
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center"><p> <?php echo PAYMENT_NOTE?></p> <br>
                                  </td>
                                </tr>
                                <tr>
                                    <td width="47%" align="right"><p><span><?php echo PUBLISH_AMOUNT;?></span></p></td>
                                    <td colspan="2" width="53%" align="left"><p>&nbsp;&nbsp;<span><?php echo $currencyArray[$currency]['symbol'];?><?php echo $cost;?></span></p></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                  </table>
			  </div>
            </td>
        </tr>
        <tr>
            <td align="left" class="maintext">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="17%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_FNAME;?><span style="color:red">*</span>
            </td>
            <td width="22%" align="left"><input type=text name="txtFirstName" id="txtFirstName" class="textbox" maxlength=50 value="<?php echo $firstName; ?>" size="30"></td>
            <td width="61%" align="left">        &nbsp;
                <input type="hidden" name="postbackCC" id="postbackCC" value="">
            </td>
        </tr>
        <tr>
            <td width="17%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_LNAME;?>
            </td>
            <td width="22%" align="left" ><input type="text" name="txtLastName" id="txtLastName" value="<?php echo($lastName); ?>" size="30" maxlength="50" class="textbox"></td>
            <td width="61%" align="left" >&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_CNO;?> <span style="color:red">*</span>
            </td>
            <td width="22%" align="left" valign="middle">
			
			<input type=text name="txtCCNumber" class="textbox medium" id="txtCCNumber" size="30" maxlength="16" onBlur="javascript:checkNumber(this);">
			<img src="images/visa_amex.gif" align="absmiddle">
			</td>
            <td width="61%" align="left">
                
            </td>
        </tr>
        <tr>
            <td width="17%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_CODE;?> <span style="color:red">*</span>
            </td>
            <td width="22%" align="left"><input type=text name="txtCode" class="textbox" id="txtCode" size=14 maxlength="4" onBlur="javascript:checkNumber(this);"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_YEAR;?> <span style="color:red">*</span> <br>
                &nbsp;&nbsp;<?php echo PUBLISH_VALID_YEAR_EG;?> 
            </td>
            <td width="22%" align="left"><input  type=text name="txtMM" class="textbox small" id="txtMM" size=3 maxlength="2" onBlur="javascript:checkNumber(this);">
                /
                <input type=text name="txtYY"  class="textbox small" id="txtYY" size=3 maxlength="2" onBlur="javascript:checkNumber(this);"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>

        <tr align="left">
            <td colspan=3  class="maintext">
              
                <h3 class="hding_11"><?php echo BILLING_ADDRESS;?></h3>
          </td>
        </tr>
        
        <tr>
            <td  align="left" class="maintext"> &nbsp;&nbsp;<?php echo PUBLISH_COMPANY;?></td>
            <td align="left"><input type=text name="txtCompany" class="textbox" id="txtCompany" size=30 maxlength=50 value="<?php echo($_POST["txtCompany"]); ?>"></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="17%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_ADDRESS;?>
            </td>
            <td width="22%" align="left"><input name="txtAddress" type="text" class="textbox" id="txtAddress" value="<?php echo($address); ?>" size="30"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%"  align="left"  height="20" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_CITY;?>
            </td>
            <td width="22%" align="left"><input type=text name="txtCity" class="textbox" id="txtCity" size=30 maxlength="40" value="<?php echo($city); ?>"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_STATE;?>
            </td>
            <td width="22%" align="left"><input type=text name="txtState" class="textbox" id="txtState" size=30 maxlength=40 value="<?php echo($state); ?>"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td class="maintext" align="left"> &nbsp;&nbsp;<?php echo PUBLISH_PH;?></td>
            <td align="left"><input type=text name="txtPhone" class="textbox" id="txtPhone" size=30 maxlength=25 value="<?php echo($phone); ?>"></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td width="17%" class="maintext" align="left">
                &nbsp;&nbsp;<?php echo PUBLISH_POSTAL;?>
            </td>
            <td width="22%" align="left"><input type=text name="txtZIP" class="textbox" id="txtZIP" size=30 maxlength=20  value="<?php echo($zipLp); ?>"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%"  align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_COUNTRY;?> 
            </td>
            <td width="22%" align="left">
                <select name="ddlCountry" id="ddlCountry" class="selectbox">
                    <?php
                    $country = $country;
                    include "includes/countries.php";
                    ?>
                </select>
                </td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td width="17%" align="left" class="maintext">
                &nbsp;&nbsp;<?php echo PUBLISH_EMAIL;?> <span style="color:red">*</span>
            </td>
            <td width="22%" align="left"><input type=text name="txtEmail" class="textbox" id="txtEmail" size=30 maxlength=255 value="<?php echo($email); ?>"></td>
            <td width="61%" align="left">&nbsp;
            </td>
        </tr>
        <tr>
            <td >
			</td>
			<td align="left" colspan="2">
                <input type="button" name="btPay" id="btPay" class="btn01 leftmr01 "  value="Pay Now" onClick="javascript:clickPayLP();" >
            </td>
        </tr>
                    <?php
                }
            }
            ?>
        
    </table>
</form>
    <?php } ?>
 
<?php
include "includes/userfooter.php";
?>