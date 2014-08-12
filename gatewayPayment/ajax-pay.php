<?php
require_once('config.php');
require_once('lib/recurly.php');

Recurly_Client::$subdomain = RECURLY_SUBDOMAIN  ;
Recurly_Client::$apiKey = RECURLY_API_KEY;
Recurly_js::$privateKey = RECURLY_PRIVATE_KEY;

$result = "success";
$error = "";
$data = array();
$accountCode = $_POST['accountCode'];
$amount = $_POST['amount'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];
 
if ($accountCode == "") {
    try {
        $account = new Recurly_Account($email);
        $account->email = $email ;
        $account->first_name = $first_name ;
        $account->last_name = $last_name ;
        $account->username = $username;
        $account->create();
        $accountCode = $email;
    }
    catch (Recurly_ValidationError $e) {
        $account = Recurly_Account::get($email);
        $account->email = $email ;
        $account->first_name = $first_name ;
        $account->last_name = $last_name ;
        $account->update();
        $accountCode=$email;
    }
} else {
    $account = Recurly_Account::get($accountCode);
    $account->email = $email ;
    $account->first_name = $first_name ;
    $account->last_name = $last_name ;
    $account->update();
}

$signature = Recurly_js::sign(array(
                'transaction' => array('amount' => $amount, 'currency' => CURRENCY_TYPE),
                'account' => array('account_code' => $accountCode)
));

$data['signature'] = $signature;
$data['result'] = $result;
echo json_encode($data);
?>