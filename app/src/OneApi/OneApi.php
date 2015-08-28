<?php namespace App\OneApi;
require __DIR__.'/lib/oneapi/client.php';

use Config;
use Log;
use Queue;
use Settings;
use App\Appointment\Models\Trimmer\SMSTrimmer;

class OneApi
{
    public function formatNumber($phone, $countryCode = '')
    {
        $countryCode = $countryCode ?: Settings::get('phone_country_code');

        // avoid formatted numbers
        if (strpos($phone, '+') !== 0
                && strpos($phone, '00') !== 0
                && strpos($phone, $countryCode) !== 0) {

            if (strpos($phone, '0') === 0) {
                $phone = ltrim($phone, '0');
            }

            $phone = $countryCode.$phone;
        }

        // remove all non-numeric characters
        return preg_replace('/[^0-9]/s', '', $phone);
    }

    public function send($from, $to, $message, $countryCode = '')
    {
        $pretending = Config::get('sms.pretend');
        if ($pretending === true) {
            // Log
            Log::info("Pretending to send SMS from {$from} to {$to}");

            return;
        }

        $trimmer = new SMSTrimmer(Settings::get('sms_length_limiter'));
        $message = $trimmer->trim($message);

        try {
            $smsClient = new \SmsClient(
                Config::get('services.oneapi.username'),
                Config::get('services.oneapi.password')
            );

            // Login
            $smsClient->login();

            // Prepare message
            $phone = static::formatNumber($to, $countryCode);
            $smsMessage = new \SMSRequest();
            $smsMessage->senderAddress = $from;
            $smsMessage->address = $phone;
            $smsMessage->message = $message;

            // Send
            Log::info('Start to send SMS', [
                'to' => $phone,
            ]);
            $smsMessageSendResult = $smsClient->sendSMS($smsMessage);
        } catch (\Exception $ex) {
            Log::error('Cannot send SMS: '.$ex->getMessage(), [
                'from'    => $from,
                'to'      => $to,
                'message' => $message,
            ]);
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
