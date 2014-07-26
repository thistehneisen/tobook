<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy <jimmy.jos@armia.com>                                      |
// +----------------------------------------------------------------------+
?>
<?php
//This is for the payment of the sale item.

// From a previous HTML Form, pass the following fields:
// $FirstName = Customer's First Name
// $LastName = Customer's Last Name
// $CardNum = Customer's Credit Card Number
// $Month = Customer's Credit Card Expiration Month (Should be 01, 02, etc.)
// $Year = Customer's Credit Card Expiration Year (Should be 2003, 2004, etc.)
// $Address = Customer's Address
// $City = Customer's City
// $State = Customer's State (Should be 2 letter code, CA, AZ, etc.)
// $Zip = Customer's Zip Code
// $Email = Customer's Email Address
// $cost = Total Price of purchase

// Check to make sure customer entered all relevant information
//For wells fargo merchants copy the below condition to the first IF LOOP
//        '|| !$Cust_ip || !$Company || !$Phone || !$Cust_id'


/*
echo $FirstName.' :'.$LastName.' :'.$Address.' :'.$City.' :'.$State.' :'.$Zip.' :'.$CardNum .' :'.
$Email .' :'.$CardCode.' :'.$Country.' :'.$Month.' :'.$Year.' :'.$Cust_ip.' :'.$Company .' :'.$Phone .' :'.$Cust_id;
*/
 

if (!$FirstName || !$LastName || !$Address || !$City || !$State || !$Zip || !$CardNum || !
$Email || !$CardCode || !$Country || !$Month || !$Year || !$Cust_ip || !$Company || !$Phone || !$Cust_id) {
$cc_flag = false;
$cc_err= "You forgot some necessary information.  Please enter the missing information." ;
//exit;
} else {
$x_customdata="Custom";

//get authorize.net informaion from database
$sql="select vname,vvalue from tbl_lookup where vname in('auth_txnkey','auth_loginid', 'auth_email', 'auth_demo')";

$result=mysql_query($sql,$con);

while($row=mysql_fetch_array($result)){

    $vname=stripslashes($row["vname"]);
    $vvalue=stripslashes($row["vvalue"]);

        switch($vname){

                case  auth_txnkey:
                      $x_tran_key = urlencode($vvalue); // Tran Key
                 break;

                case  auth_loginid:
                 $x_Login = urlencode($vvalue); // Replace LOGIN with your login
                 break;

                case  auth_email:
                 $x_Merchant_Email = urlencode($vvalue); // Replace MERCHANT_EMAIL with your email
                 break;

                case  auth_pass:
                 $x_Password = urlencode($vvalue); // Replace PASS with your password
                 break;

                case  auth_demo:
                 $txtAuthDemo = urlencode($vvalue); // Replace PASS with your password
                 break;
        }
}


//$x_Login= urlencode("testing"); // Replace LOGIN with your login
//$x_tran_key = urlencode("3dpDcdGG2GMSRc1n"); // Tran Key
//$x_Password= urlencode(""); // Replace PASS with your password
$x_Delim_Data= urlencode("TRUE");
$x_Delim_Char= urlencode(",");
$x_Encap_Char= urlencode("");
$x_Type= urlencode("AUTH_CAPTURE");

$x_ADC_Relay_Response = urlencode("FALSE");
if($txtAuthDemo=="YES")
   $x_Test_Request= urlencode("TRUE");
else
   $x_Test_Request= urlencode("FALSE");
#
# Customer Information
#

//Section WFM
//Uncomment WFM section for Wells Fargo merchant section
$x_cust_ip = urlencode($Cust_ip);
$x_company = urlencode($Company);
$x_phone = urlencode($Phone);
$x_cust_id = urlencode($Cust_id);

$x_Method= urlencode("CC");
$x_Amount= urlencode($cost);
$x_First_Name= urlencode($FirstName);
$x_Last_Name= urlencode($LastName);
$x_Card_Num= urlencode($CardNum);
$ExpDate = ($Month . $Year);
$x_Exp_Date= urlencode($ExpDate);
$x_card_code =urlencode($CardCode);

$x_Address= urlencode($Address);
$x_City= urlencode($City);
$x_State= urlencode($State);
$x_Zip= urlencode($Zip);
$x_country=urlencode($Country);

//$x_invid = urlencode($invid);

$x_Email= urlencode($Email);
$x_Email_Customer= urlencode("TRUE");
//$x_Merchant_Email= urlencode("jimmy.jos@armia.com"); //  Replace MERCHANT_EMAIL with the merchant email address
#
# Build fields string to post
#
$fields="x_Version=3.1&x_Login=$x_Login&x_tran_key=$x_tran_key&x_Delim_Data=$x_Delim_Data&x_Delim_Char=$x_Delim_Char&x_Encap_Char=$x_Encap_Char";
$fields.="&x_Type=$x_Type&x_Test_Request=$x_Test_Request&x_Method=$x_Method&x_Amount=$x_Amount&x_First_Name=$x_First_Name";
$fields.="&x_Last_Name=$x_Last_Name&x_Card_Num=$x_Card_Num&x_Exp_Date=$x_Exp_Date&x_card_code=$x_card_code&x_Address=$x_Address&x_City=$x_City&x_State=$x_State&x_Zip=$x_Zip&x_country=$x_country&x_Email=$x_Email&x_Email_Customer=$x_Email_Customer&x_Merchant_Email=$x_Merchant_Email&x_ADC_Relay_Response=$x_ADC_Relay_Response&x_invid=$x_invid&x_cust_ip=$x_cust_ip&x_company=$x_company&x_phone=$x_phone&x_cust_id=$x_cust_id";
if($x_Password!='')
{
$fields.="&x_Password=$x_Password";
}
#
# Start CURL session
#
$agent	= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
$ref	= $_SERVER['PHP_SELF'];//"$rootserver/paymentord.php"; // Replace this URL with the URL of this script

$ch		= curl_init();
if ($txtAuthDemo == "YES") {
	curl_setopt($ch, CURLOPT_URL, "https://test.authorize.net/gateway/transact.dll");
} else {
	curl_setopt($ch, CURLOPT_URL, "https://secure.authorize.net/gateway/transact.dll");
}
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_NOPROGRESS, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_REFERER, $ref);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$buffer = curl_exec($ch);
curl_close($ch);

// This section of the code is the change from Version 1.
// This allows this script to process all information provided by Authorize.net...
// and not just whether if the transaction was successful or not

// Provided in the true spirit of giving by Chuck Carpenter (Chuck@MLSphotos.com)
// Be sure to email him and tell him how much you appreciate his efforts for PHP coders everywhere

$return = preg_split("/[,]+/", "$buffer"); // Splits out the buffer return into an array so . . .
$details = $return[0]; // This can grab the Transaction ID at position 1 in the array

/*$carholdername=$return[14];
$carholderlname=$return[15];
$carholderaddress=$return[16];
foreach($return as $key=>$value){
  echo "key=$key and value=$value <br>";
}
exit;
*/
// Change the number to grab additional information.  Consult the AIM guidelines to see what information is provided in each position.

// For instance, to get the Transaction ID from the returned information (in position 7)..
// Simply add the following:
// $x_trans_id = $return[6];

// You may then use the switch statement (or other process) to process the information provided
// Example below is to see if the transaction was charged successfully

//$details	= 1;  added for checking pruporse only
        switch ($details)
        {
                case "1": // Credit Card Successfully Charged
                                //header("location:http://www.cinu.org/testcredit/success.php?n=$carholdername&l=$carholderlname&add=$carholderaddress");
                                $cc_flag=true;
                                $cc_tran=$return[6];
                                break;
                case "2":
                                $cc_flag = false;
                                $cc_err="The card has been declined";
                                $cc_err .="<br>" . $return[3];
                                break;
                case "4":
                                $cc_flag = false;
                                $cc_err="The card has been held for review";
                                $cc_err .="<br>" . $return[3];
                                break;
                default: // Credit Card Not Successfully Charged
                                //header ("Location: http://www.armia.com/contactus.htm"); // Change this address with the URL of your 'Error' page
                                $cc_flag = false;
                                $cc_err="Error";
                                $cc_err .="<br>" . $return[3];
                                break;
        }
}
?>