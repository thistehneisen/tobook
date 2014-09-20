<?php namespace App\Appointment\Models\Observer;
use App, View, Confide, Mail;
use Carbon\Carbon;
class EmailObserver implements \SplObserver
{

    /**
     * Is Email confirmation enabled?
     *
     * @var boolean
     */
    private $isEnabled;

    /**
     * Service info: name, date (start - end)
     *
     * @var string
     */
    private $serviceInfo;

    public function init($subject)
    {
        $this->setIsEnabled($subject);
        $this->setServiceInfo($subject);
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setIsEnabled($subject)
    {
        $this->isEnabled = (bool) $subject->user->asOptions['confirm_email_enable'];
        return $this;
    }

    public function setServiceInfo($subject)
    {
        $msg = $subject->user->asOptions['confirm_tokens_client'];

          //Start time for consumer is after service before
        $befofe = $after = 0;
        if(!empty($subject->bookingServices()->first()->serviceTime->id)){
            $serviceTime = $subject->bookingServices()->first()->serviceTime;
            $befofe      = $serviceTime->before;
            $after       = $serviceTime->after;
        } else {
            $service = $subject->bookingServices()->first()->service;
            $before  = $service->before;
            $after   = $service->after;
        }
        $start_at = $subject->getStartAt()->addMinutes($before);
        $end_at   = $subject->getEndAt()->subMinutes($after);

        $serviceInfo = "{service}, {date} ({start} - {end})";
        $serviceInfo = str_replace('{service}', $subject->bookingServices()->first()->service->name, $serviceInfo);
        $serviceInfo = str_replace('{date}', $subject->date, $serviceInfo);
        $serviceInfo = str_replace('{start}', $start->toTimeString(), $serviceInfo);
        $serviceInfo = str_replace('{end}', $end->toTimeString(), $serviceInfo);
        $this->serviceInfo = $serviceInfo;
        return $this;
    }

    public function update(\SplSubject $subject) {
        $this->init($subject);
        if ($this->isEnabled) {
            $this->sendConsumerEmail($subject);
            $this->sendEmployeeEmail($subject);
        }
    }

    public function getEmailBody($subject, $body)
    {
        $body  = str_replace('{Services}', $this->serviceInfo, $body));
        $body  = str_replace('{Name}',$subject->consumer->name, $body);
        $body  = str_replace('{BookingID}', $subject->uuid, $body);
        $body  = str_replace('{Phone}', $subject->consumer->phone, $body);
        $body  = str_replace('{Email}', $subject->consumer->email, $body);
        $body  = str_replace('{Notes}', $subject->notes, $body);
        return $body;
    }

    public function sendConsumerEmail($subject)
    {
        $emailSubject   = $subject->user->asOptions['confirm_subject_client'];
        $body           = $subject->user->asOptions['confirm_tokens_client'];
        $body           = $this->getEmailBody($subject, $body);

        $email['title'] = $emailSubject;
        $email['body']  = nl2br($body);

        Mail::send('modules.as.emails.confirm', $email, function($message) use($subject, $emailSubject)
        {
            $message->to($subject->consumer->email, $subject->consumer->name)->subject($emailSubject);
        });
    }

    public function sendEmployeeEmail($subject)
    {
        $emailSubject   = $subject->user->asOptions['confirm_subject_employee'];
        $body           = $subject->user->asOptions['confirm_tokens_employee'];
        $body           = $this->getEmailBody($subject, $body);

        $email['title'] = $emailSubject;
        $email['body']  = nl2br($body);

        $employee = $subject->bookingServices()->first()->employee;

        if ($employee->is_subscribed_email) {
            Mail::send('modules.as.emails.confirm', $email, function($message) use($employee, $emailSubject)
            {
                $message->to($employee->email, $employee->name)->subject($emailSubject);
            });
        }
    }
}
