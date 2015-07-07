<?php namespace App\Appointment\Models\Observer;

use App, View, Confide, Mail, Log, Settings;
use Queue;
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
        $this->serviceInfo = $subject->getServiceInfo();
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
        $cancelURL = route('as.bookings.cancel', ['uuid' => $subject->uuid]);
        $address = sprintf('%s, %s %s',
            $subject->consumer->address,
            $subject->consumer->city,
            $subject->consumer->postcode);

        $body = str_replace('{Services}', $this->serviceInfo, $body);
        $body = str_replace('{ServicesDescription}', $subject->getServicesDescription(), $body);
        $body = str_replace('{Name}',$subject->consumer->name, $body);
        $body = str_replace('{BookingID}', $subject->uuid, $body);
        $body = str_replace('{Phone}', $subject->consumer->phone, $body);
        $body = str_replace('{Email}', $subject->consumer->email, $body);
        $body = str_replace('{Notes}', $subject->notes, $body);
        $body = str_replace('{CancelURL}', $cancelURL, $body);
        $body = str_replace('{Address}', $address, $body);

        $depositPayment = (bool) Settings::get('deposit_payment');

        $depositAmount = (!empty($subject->deposit)) ? $subject->deposit : 0;
        $body = str_replace('{Deposit}', $depositAmount, $body);

        return $body;
    }

    public function sendConsumerEmail($subject)
    {
        if (empty($subject->consumer->email)
                || (!empty($subject->consumer->receive_email)
                    && !(bool)$subject->consumer->receive_email)) {
            return;
        }

        $emailSubject = $subject->user->asOptions['confirm_subject_client'];
        $body         = $subject->user->asOptions['confirm_tokens_client'];
        $body         = $this->getEmailBody($subject, $body);
        $receiver     = $subject->consumer->email;
        $receiverName = $subject->consumer->name;

        Queue::push(function ($job) use ($emailSubject, $body, $receiver, $receiverName) {
            Mail::send('modules.as.emails.confirm', [
                'title' => $emailSubject,
                'body' => nl2br($body)
            ], function($message) use ($emailSubject, $receiver, $receiverName){
                $message->subject($emailSubject);
                $message->to($receiver, $receiverName);
            });
            $job->delete();
        });

        Log::info('Enqueue to send email to consumer', [
            'to' => $subject->consumer->email,
        ]);
    }

    public function sendEmployeeEmail($subject)
    {
        $employee = $subject->bookingServices()->first()->employee;
        if (!$employee->isReceivedCalendarInvitation) {

            if (empty($employee->email) ||
                (!empty($employee->is_subscribed_email) && !(bool)$employee->is_subscribed_email) {
                return;
            }

            //@see http://laravel.com/docs/4.2/queues#queueing-closures

            $emailSubject = $subject->user->asOptions['confirm_subject_employee'];
            $body         = $subject->user->asOptions['confirm_tokens_employee'];
            $body         = $this->getEmailBody($subject, $body);
            $receiver     = $employee->email;
            $receiverName = $employee->name;


            Queue::push(function ($job) use ($emailSubject, $body, $receiver, $receiverName) {
                Mail::send('modules.as.emails.confirm', [
                    'title' => $emailSubject,
                    'body' => nl2br($body)
                ], function($message) use ($emailSubject, $receiver, $receiverName){
                    $message->subject($emailSubject);
                    $message->to($receiver, $receiverName);
                });
                $job->delete();
            });

            Log::info('Enqueue to send email to employee', [
                'to' => $employee->email,
            ]);
        }
    }
}
