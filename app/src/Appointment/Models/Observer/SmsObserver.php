<?php namespace App\Appointment\Models\Observer;
use App, View, Confide, Sms;
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

    private $from = 'varaa.com';

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
        $this->isEnabled = (bool) $subject->user->asOptions['confirm_email_enable'];
        return $this;
    }

    public function setCode($subject)
    {
       $this->code =  $subject->user->asOptions['confirm_sms_country_code'];
       return $this;
    }

    public function getSendNumber($phone)
    {
        if (strpos($phone, '0') === 0 ) {
            $phone = ltrim($phone, '0');
        }
        $phone = (empty($this->code)) ? $this->code . $phone : '358' . $phone;
        return $phone;
    }

    protected function setServiceInfo($subject)
    {
        $befofe = $after = 0;

        if(!empty($this->subject->bookingServices()->first()->serviceTime->id)){
            $serviceTime = $this->subject->bookingServices()->first()->serviceTime;
            $befofe      = $serviceTime->before;
            $after       = $serviceTime->after;
        } else {
            $service = $this->subject->bookingServices()->first()->service;
            $before  = $service->before;
            $after   = $service->after;
        }

        //Start time for consumer is after service before
        $start = $subject->getStartAt()->addMinutes($before);
        $end   = $subject->getEndAt()->subMinutes($after);

        $serviceInfo = "{service}, {date} ({start} - {end})";
        $serviceInfo = str_replace('{service}', $subject->bookingServices()->first()->service->name, $serviceInfo);
        $serviceInfo = str_replace('{date}', $subject->date, $serviceInfo);
        $serviceInfo = str_replace('{start}', $start->toTimeString(), $serviceInfo);
        $serviceInfo = str_replace('{end}', $end->toTimeString(), $serviceInfo);

        $this->serviceInfo = $serviceInfo;
        return $this;
    }

    public function update(\SplSubject $subject)
    {
        $this->init($subject);

        if($this->isEnabled){
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
        $msg = $subject->user->asOptions['confirm_consumer_sms_message'];
        $to  = $this->getSendNumber($subject->consumer->phone);
        $msg = str_replace('{Services}', $this->info, $msg);
        Sms::send($this->from, $to, $msg);
    }

    protected function sendToEmployee($subject)
    {
        //Does not send sms for employee in backend
        if ($this->isBackend) {
            return;
        }

        if ($subject->employee->is_subscribed_sms) {
            $to  = $this->getSendNumber($subject->employee->phone);
            $msg = $subject->user->asOptions['confirm_employee_sms_message'];
            $msg = str_replace('{Services}', $this->info, $msg);
            $msg = str_replace('{Consumer}', $subject->consumer->name, $msg);
            Sms::send($this->from, $to, $msg);
        }
    }
}
