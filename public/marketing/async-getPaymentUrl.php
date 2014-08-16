<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$ownerId = mysql_escape_string($_POST['ownerId']);
$siteId = mysql_escape_string($_POST['siteId']);
$planCode = "sm_1";

$sql = "select *
          from tbl_payment_history
         where plan_code = '$planCode'
           and owner = $ownerId
         order by payment_history desc";
$dataResult = $db->queryArray($sql);
if( $dataResult > 0 ){
    $accountCode = $dataResult[0]['account_code'];
}else{
    $accountCode = "";
}

$siteUrl = $_SERVER['SERVER_NAME'];
$paymentUrl = "http://".$siteUrl."/gatewayPayment/paymentSiteManager.php?accountCode=$accountCode&userId=$ownerId&planCode=$planCode";

$data['paymentUrl'] = $paymentUrl;
$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
