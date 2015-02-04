<?php namespace App\Appointment\Models\Observer;

use App, View, Confide, Sms, Log, Config;
use Carbon\Carbon;

class SmsObserver implements \SplObserver {

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
       $this->code = $subject->user->asOptions['confirm_sms_country_code'];
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
        if (empty($subject->consumer->phone) || (!$subject->consumer->receive_sms)) {
            return;
        }

        $msg = $subject->user->asOptions['confirm_consumer_sms_message'];
        $cancelURL = route('as.bookings.cancel', ['uuid' => $subject->uuid]);
        $address = sprintf('%s, %s %s',
            $subject->consumer->address,
            $subject->consumer->city,
            $subject->consumer->postcode);
        $msg = str_replace('{Services}', $this->serviceInfo, $msg);
        $msg = str_replace('{CancelURL}', $cancelURL, $msg);
        $msg = str_replace('{Address}', $address, $msg);

        Sms::send(Config::get('sms.from'), $subject->consumer->phone, $msg, $this->code);
    }

    protected function sendToEmployee($subject)
    {
        // Does not send sms for employee in backend
        if ($this->isBackend || (!$subject->employee->is_subscribed_sms)) {
            return;
        }

        $msg = $subject->user->asOptions['confirm_employee_sms_message'];
        $msg = str_replace('{Services}', $this->serviceInfo, $msg);
        $msg = str_replace('{Consumer}', $subject->consumer->name, $msg);
        Sms::send(Config::get('sms.from'), $subject->employee->phone, $msg, $this->code);
    }
}
