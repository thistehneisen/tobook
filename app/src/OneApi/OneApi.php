<?php namespace App\OneApi;
require __DIR__.'/lib/oneapi/client.php';

use Config, Log;

class OneApi
{
    public function formatNumber($phone, $countryCode = '')
    {
        if (strpos($phone, '0') === 0) {
            $phone = ltrim($phone, '0');
        }

        // TODO: this should be user config?
        $countryCode = $countryCode ?: '358';
        $phone = $countryCode . $phone;
        return $phone;
    }

    public function send($from, $to, $message, $countryCode = '')
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
        $smsMessage->address       = static::formatNumber($to, $countryCode);
        $smsMessage->message       = $message;

        // Send
        $smsMessageSendResult = $smsClient->sendSMS($smsMessage);
    }
}
