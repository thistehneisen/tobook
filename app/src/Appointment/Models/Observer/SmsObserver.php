<?php namespace App\Appointment\Models\Observer;

use App;
use Config;
use Queue;
use Settings;
use Sms;
use Log;

class SmsObserver implements \SplObserver
{
    /**
     * Use to distinguish between front end and backend booking
     *
     * @var boolean
     */
    private $isBackend = false;

    /**
     * Is SMS confirmation enabled?
     *
     * @var boolean
     */
    private $isEnabled;

    /**
     * Country code
     *
     * @var string
     */
    private $code;

    /**
     * Service info: name, date (start - end)
     *
     * @var string
     */
    private $serviceInfo;

    public function __construct($isBackend = false)
    {
        $this->isBackend = $isBackend;
    }

    public function setIsBackend($value)
    {
        $this->isBackend = (bool) $value;

        return $this;
    }

    public function init($subject)
    {
        $this->setIsEnabled($subject);
        $this->setCode($subject);
        $this->setServiceInfo($subject);
    }

    public function setIsEnabled($subject)
    {
        $this->isEnabled = (bool) $subject->user->asOptions['confirm_sms_enable'];

        return $this;
    }

    public function setCode($subject)
    {
       $this->code = Settings::get('phone_country_code');

       return $this;
    }

    protected function setServiceInfo($subject)
    {
        $this->serviceInfo = $subject->getServiceInfo();

        return $this;
    }

    public function update(\SplSubject $subject)
    {
        $this->init($subject);
        if ($this->isEnabled) {
            //Init service info for message
            $this->setServiceInfo($subject);
            //Send SMS to consumer
            $this->sendToConsumer($subject);
            //Send SMS to employee
            $this->sendToEmployee($subject);

        }
    }

    protected function sendToConsumer($subject)
    {
        if (empty($subject->consumer->phone) || (isset($subject->consumer->receive_sms)
            && $subject->consumer->receive_sms === false)) {
            return;
        }

        $msg = $subject->user->asOptions['confirm_consumer_sms_message'];
        $cancelURL = route('as.bookings.cancel', ['uuid' => $subject->uuid]);
        $address = sprintf('%s, %s %s',
            $subject->consumer->address,
            $subject->consumer->city,
            $subject->consumer->postcode);
        $msg = str_replace('{Services}', $subject->getServiceInfo(), $msg);
        $msg = str_replace('{ServicesFull}', $subject->getServiceInfo(true), $msg);
        $msg = str_replace('{CancelURL}', $cancelURL, $msg);
        $msg = str_replace('{Address}', $address, $msg);

        $depositAmount = (!empty($subject->deposit)) ? $subject->deposit : 0;
        $msg = str_replace('{Deposit}', $depositAmount, $msg);

        $code = $this->code;
        Log::info('Enqueue to send SMS to consumer', [
            'to' => $subject->consumer->phone,
        ]);
        Queue::push(function ($job) use ($subject, $msg, $code) {
            Sms::send(Config::get('sms.from'), $subject->consumer->phone, $msg, $code);
            $job->delete();
        });
    }

    protected function sendToEmployee($subject)
    {
        // Does not send sms for employee in backend
        if ($this->isBackend || (!$subject->employee->is_subscribed_sms) || empty($subject->employee->phone)) {
            return;
        }

        $msg = $subject->user->asOptions['confirm_employee_sms_message'];
        $msg = str_replace('{Services}', $this->serviceInfo, $msg);
        $msg = str_replace('{Consumer}', $subject->consumer->name, $msg);
        $depositAmount = (!empty($subject->deposit)) ? $subject->deposit : 0;
        $msg = str_replace('{Deposit}', $depositAmount, $msg);

        $code = $this->code;
        Log::info('Enqueue to send SMS to employee', [
            'to' => $subject->employee->phone,
        ]);

        //@see http://laravel.com/docs/4.2/queues#queueing-closures
        $phone = $subject->employee->phone;

        Queue::push(function ($job) use ($phone, $msg, $code) {
            Sms::send(Config::get('sms.from'), $phone, $msg, $code);
            $job->delete();
        });
    }
}
