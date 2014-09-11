<?php namespace App\Appointment\Models\Observer;
use App, View, Confide, Sms;
use Carbon\Carbon;

class SmsObserver implements \SplObserver {

    public function update(\SplSubject $subject) {
        $smsEnabled = (bool) $subject->user->asOptions['confirm_sms_enable'];
        if($smsEnabled){

            //Start time for consumer is after service before
             $befofe = 0;
             $after = 0;
            if(!empty($subject->bookingServices()->first()->serviceTime)){
                $befofe = $subject->bookingServices()->first()->serviceTime->before;
                $after  = $subject->bookingServices()->first()->serviceTime->after;
            } else {
                $before = $subject->bookingServices()->first()->service->before;
                $after  = $subject->bookingServices()->first()->service->after;
            }
            $start_at = with(new Carbon($subject->start_at))->addMinutes($before);
            $end_at   = with(new Carbon($subject->end_at))->subMinutes($after);

            $serviceInfo = sprintf("%s, %s (%s - %s)",
                $subject->bookingServices()->first()->service->name,
                $subject->date,
                $start_at->toTimeString(),
                $end_at->toTimeString());

            $smsMessage =  $subject->user->asOptions['confirm_consumer_sms_message'];
            $from = 'varaa.com';
            $to = $subject->consumer->phone;
            if (strpos($to, '0') === 0 ) {
                $to = ltrim($to, '0');
            }

            $code = $subject->user->asOptions['confirm_sms_country_code'];
            $to = (empty($code)) ? $code . $to : '358' . $to;
            $msg  = str_replace('{Services}', $serviceInfo, $smsMessage);
            //$msg  = str_replace('{Consumer}', $subject->consumer->name, $msg);
            Sms::send($from, $to, $msg);
        }
    }
}
