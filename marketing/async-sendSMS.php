<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$title = mysql_escape_string($_POST['title']);
$content = mysql_escape_string($_POST['content']);
$ownerId = mysql_escape_string($_POST['ownerId']);
$customerList = $_POST['customerList'];
$marketingType = 'S';

$sql = "insert into tbl_marketing_message(title, content, type, created_time, updated_time)
         value ( '$title', '$content', '$marketingType', now(), now())";
$db->queryInsert($sql);

$marketingMessage = $db->getPrevInsertId();

$sql = "select *
          from tbl_owner_premium
         where owner = $ownerId
           and plan_group_code = 'mt'";
$dataResult = $db->queryArray($sql);
$credits = $dataResult[0]['credits'];
$accountCode = $dataResult[0]['account_code'];

$payable = "Y";
if ($credits >= count($customerList) * CREDITS_SMS) {
    $payable = "Y";
    $remainCredits = $credits - count($customerList) * CREDITS_SMS;
} else {
    $recurlyAmount = round((count($customerList) * CREDITS_SMS - $credits) / CREDITS_PRICE) + 1;
    $paymentResult = onePay($accountCode, $recurlyAmount);
    if ($paymentResult->result == "success") {
        $remainCredits = $credits - count($customerList) * CREDITS_SMS + $recurlyAmount * CREDITS_PRICE;
        $payable = "Y";
    } else {
        $payable = "N";
    }
}
 
if ($payable == "Y") {
    for ($i = 0 ; $i < count($customerList); $i++) {
        $customerId = $customerList[$i]['customerId'];
        $planGroupCode = $customerList[$i]['planGroupCode'];
        $destination =  $customerList[$i]['destination'];
        $return = MT_sendSMS($destination, $title, $content);
        $messageId = $return[0]->messageid;
        $status = $return[0]->status;

        $sql = "insert into tbl_marketing_history(owner, customer, plan_group_code, destination, messageId, status, marketing_type, marketing_message, created_time, updated_time)
                 value ($ownerId, $customerId, '$planGroupCode', '$destination', '$messageId', '$status', '$marketingType', $marketingMessage, now(), now())";
        $db->queryInsert($sql);
    }
    $credits = $credits - count($customerList) * CREDITS_SMS;
    $sql = "update tbl_owner_premium
               set credits = $credits
             where owner = $ownerId
               and plan_group_code = 'mt'";
    $db->query($sql);
    $data['credits'] = $remainCredits;
} else {
    $result = "failed";
    $error = "CREDIT_NOT_ENOUGH";
}

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>