<?php namespace App\OneApi;
require __DIR__.'/lib/oneapi/client.php';

use Config;

class OneApi
{
    public function send($from, $to, $message)
    {
        $smsClient = new \SmsClient(
            Config::get('varaa.oneapi.username'),
            Config::get('varaa.oneapi.password')
        );

        // Login
        $smsClient->login();

        // Prepare message
        $smsMessage = new \SMSRequest();
        $smsMessage->senderAddress = $from;
        $smsMessage->address       = $to;
        $smsMessage->message       = $message;

        // Send
        $smsMessageSendResult = $smsClient->sendSMS($smsMessage);
    }
}
