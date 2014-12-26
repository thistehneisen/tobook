<?php namespace App\OneApi;
require __DIR__.'/lib/oneapi/client.php';

use Config, Log, Queue;

class OneApi
{
    public function formatNumber($phone, $countryCode = '')
    {
        // formatted numbers
        if (strpos($phone, '+') === 0 || strpos($phone, '00') === 0) {
            return $phone;
        }

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
            Log::info("Pretending to send SMS from {$from} to {$to}");
            return;
        }

        try {
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
        } catch (\Exception $ex) {
            Log::warning("Can't send SMS to: {$smsMessage->address}", [$ex]);
        }
    }

    public function scheduledSend($job, $data)
    {
        list($from, $to, $message, $countryCode) = $data;
        static::send($from, $to, $message, $countryCode);

        $job->delete();
    }

    public function queue($from, $to, $message, $countryCode = '')
    {
        Queue::push('App\OneApi\OneApi@scheduledSend', [
            $from, $to, $message, $countryCode
        ]);
    }
}
