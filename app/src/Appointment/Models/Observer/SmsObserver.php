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
     * Reused in other method
     *
     * @var App\Appointment\Models\Booking
     */
    private $subject;

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
    private $info;

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

    public function setSubject($subject)
    {
         $this->subject = $subject;
         return $this;
    }

    public function setCode()
    {
       $this->code =  $this->subject->user->asOptions['confirm_sms_country_code'];
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

    protected function setServiceInfo()
    {
        $befofe = $after = 0;

        if(!empty($this->subject->bookingServices()->first()->serviceTime)){
            $serviceTime = $this->subject->bookingServices()->first()->serviceTime;
            $befofe      = $serviceTime->before;
            $after       = $serviceTime->after;
        } else {
            $service = $this->subject->bookingServices()->first()->service;
            $before  = $service->before;
            $after   = $service->after;
        }

        //Start time for consumer is after service before
        $start = with(new Carbon($this->subject->start_at))->addMinutes($before);
        $end   = with(new Carbon($this->subject->end_at))->subMinutes($after);

        $info = "{service}, {date} ({start} - {end})";
        $info = str_replace('{service}', $this->subject->bookingServices()->first()->service->name, $info);
        $info = str_replace('{date}', $this->subject->date, $info);
        $info = str_replace('{start}', $start->toTimeString(), $info);
        $info = str_replace('{end}', $end->toTimeString(), $info);

        $this->info = $info;
        return $this;
    }

    public function update(\SplSubject $subject)
    {
        $this->setSubject($subject);
        $this->setCode();

        $isEnabled = (bool) $this->subject->user->asOptions['confirm_sms_enable'];

        if($isEnabled){
            //Init service info for message
            $this->setServiceInfo();
            //Send SMS to consumer
            $this->sendToConsumer();
            //Send SMS to employee
            $this->sendToEmployee();

        }
    }

    protected function sendToConsumer()
    {
        $msg =  $this->subject->user->asOptions['confirm_consumer_sms_message'];
        $to = $this->getSendNumber($this->subject->consumer->phone);
        $msg  = str_replace('{Services}', $this->info, $msg);
        Sms::send($this->from, $to, $msg);
    }

    protected function sendToEmployee()
    {
        //Does not send sms for employee in backend
        if ($this->isBackend) {
            return;
        }

        if ($this->subject->employee->is_subscribed_sms) {
            $to  = $this->getSendNumber($this->subject->employee->phone);
            $msg = $this->subject->user->asOptions['confirm_employee_sms_message'];
            $msg = str_replace('{Services}', $this->info, $msg);
            $msg = str_replace('{Consumer}', $this->subject->consumer->name, $msg);
            Sms::send($this->from, $to, $msg);
        }
    }
}
