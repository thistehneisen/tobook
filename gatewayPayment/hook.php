<?php
require_once("../DB_Connection.php");

require_once('config.php');
require_once('lib/recurly.php');
Recurly_Client::$subdomain = RECURLY_SUBDOMAIN;
Recurly_Client::$apiKey = RECURLY_API_KEY;
Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;

$post_xml = file_get_contents ("php://input");
$notification = new Recurly_PushNotification($post_xml);
//each webhook is defined by a type
switch ($notification->type) {
    case "new_account_notification":
        break;
    case "new_subscription_notification":
        break;
    case "billing_info_updated_notification":
        break;
    case "successful_payment_notification":
        $username = $notification->account->username;
        $arr = explode(":", $username);
        $ownerId = $arr[0];
        $planCode = $arr[1];
        $planGroupCode = substr($planCode, 0, 2);
        $planPeriodType = substr($planCode, 2);
         
        $transactionId = $notification->transaction->id;
        $amount = $notification->transaction->amount_in_cents / 100;
        $accountCode = $notification->account->account_code;
        $status = 'Y';
        if ($planGroupCode == "sm") {
            $expiredMonth = 12;
        } elseif ($planPeriodType == "_month") {
            $expiredMonth = 1;
        } elseif ($planPeriodType == "_year") {
            $expiredMonth = 12;
        } else {
            $expiredMonth = 0;
        }
        $expiredTime = $notification->subscription->current_period_ends_at;

        $sql = "insert into tbl_payment_history( owner, plan_code, amount, transaction_id, account_code, status, created_time, updated_time)
                values ( $ownerId, '$planCode', $amount, '$transactionId', '$accountCode', '$status', now(), now())";
        $db->queryInsert($sql);
        $sql = "select *
                  from tbl_owner_premium
                 where owner = '$ownerId'
                   and plan_group_code = '$planGroupCode'";
        $dataResult = $db->queryArray($sql);
        if ($planGroupCode == "mt") {
            $credits = $amount * CREDITS_PRICE;
        } else {
            $credits = 0;
        }
         
        if ($dataResult == null) {
            $sql = "insert into tbl_owner_premium( owner, plan_group_code, plan_code, account_code, credits, expired_time, updated_time)
                    values ( $ownerId, '$planGroupCode', '$planCode', '$accountCode', $credits, date_add( now(), interval $expiredMonth month) , now())";
            $db->queryInsert($sql);
        } else {
            $sql = "update tbl_owner_premium
                       set expired_time = date_add( expired_time, interval $expiredMonth month)
                         , credits = credits + $credits
                     where owner = '$ownerId'
                       and plan_group_code = '$planGroupCode'";
            $db->query($sql);
        }
        break;
}
?>