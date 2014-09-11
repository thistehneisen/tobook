<?php namespace App\Appointment\Models\Observer;
use App, View, Confide, Mail;
use Carbon\Carbon;
class EmailObserver implements \SplObserver {

    public function update(\SplSubject $subject) {
        $emailEnabled = (bool) $subject->user->asOptions['confirm_email_enable'];

        if($emailEnabled){
            $emailSubject = $subject->user->asOptions['confirm_subject_client'];
            $body    = $subject->user->asOptions['confirm_tokens_client'];

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

            $email['title'] = $emailSubject;
            $email['body']  = nl2br(str_replace('{Services}', $serviceInfo, $body));

            Mail::send('modules.as.emails.confirm', $email, function($message) use($subject, $emailSubject)
            {
                $message->to($subject->consumer->email, $subject->consumer->name)->subject($emailSubject);
            });
        }
    }
}
