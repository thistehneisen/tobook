<?php namespace App\Appointment\Models\Observer;
use App, View, Confide, Mail;
use Carbon\Carbon;
class EmailObserver implements \SplObserver {

    public function update(\SplSubject $subject) {
        $emailEnabled = (bool) $subject->user->asOptions['confirm_email_enable'];

        if($emailEnabled){
            $emailSubject = $subject->user->asOptions['confirm_subject_client'];
            $body    = $subject->user->asOptions['confirm_tokens_client'];

            $serviceInfo = sprintf("%s, %s (%s - %s)",
                            $subject->bookingServices()->first()->service->name,
                            $subject->date,
                            $subject->start_at,
                            $subject->end_at);

            $email['title'] = $emailSubject;
            $email['body']  = nl2br(str_replace('{Services}', $serviceInfo, $body));

            Mail::send('modules.as.emails.confirm', $email, function($message) use($subject, $emailSubject)
            {
                $message->to($subject->consumer->email, $subject->consumer->name)->subject($emailSubject);
            });
        }
    }
}
