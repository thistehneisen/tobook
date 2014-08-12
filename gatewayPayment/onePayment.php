<?php
error_reporting(E_ERROR);
require_once('config.php');
require_once('lib/recurly.php');

function onePay($accountCode, $amount) {
    Recurly_Client::$subdomain = RECURLY_SUBDOMAIN  ;
    Recurly_Client::$apiKey = RECURLY_API_KEY;
    Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;
    $transaction = new Recurly_Transaction();
    try {
        $transaction->amount_in_cents = $amount*100;
        $transaction->currency = 'EUR';

        $transaction->account = new Recurly_Account();
        $transaction->account->account_code = $accountCode;
        $transaction->create();
        $data->result = "success";
        $data->error = "";
    } catch(Exception $e) {
        $data->result = "failed";
        $data->error = get_class($e) . ': ' . $e->getMessage();
    }
    return $data;
}
?>

