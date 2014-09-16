<?php namespace App\OneApi;
require __DIR__.'/lib/oneapi/client.php';

use Config, Log;

class OneApi
{
    public function send($from, $to, $message)
    {
        $pretending = Config::get('sms.pretend');
        if ($pretending === true) {
            // Log
            Log::info("Pretending to send SMS to: {$to}");
            return;
        }

        $smsClient = new \SmsClient(
            Config::get('services.oneapi.username'),
            Config::get('services.oneapi.password')
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
