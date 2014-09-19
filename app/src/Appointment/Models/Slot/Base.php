<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Booking;

class Base implements Strategy
{
     /**
     * These variables to use as a dictionary to easy to get back
     *  and limit access to db in for loop
     */
    private $bookedSlot      = [];
    private $bookingList     = [];
    private $freetimeSlot    = [];
    private $freetimesCache  = [];
    private $customTimeSlot  = [];
    private $customTimeCache = [];


    /**
     * @var string
     */
    private $date;

    /**
     * Carbon object of the date
     *
     * @var Carbon\Carbon
     */
    private $dateObj;

    /**
     * @var int
     */
    private $hour;

     /**
     * @var int
     */
    private $minute;

    /**
     * Current row need to determined the class
     *
     * @var Carbon\Carbon
     */
    private $rowTime;

    /**
     * The current employee of time cell
     * @var \App\Appointment\Models\Employee
     */
    private $employee;

    /**
     * The current service for time cell
     * @var \App\Appointment\Models\Service
     */
    private $service;

    /**
     * The initital class
     * @var string
     */
    private $class = 'inactive';

    protected function init($date, $hour, $minute, $employee, $service = null)
    {
        $this->date     = $date;
        $this->dateObj  = Carbon::createFromFormat('Y-m-d', $date);
        $this->hour     = (int) $hour;
        $this->minute   = (int) $minute;
        $this->service  = $service;
        $this->employee = $employee;
        $this->rowTime  = Carbon::createFromTime($hour, $minute, 0, Config::get('app.timezone'));
        return $this;
    }

    public function determineClass($date, $hour, $minute, $employee, $service = null){
        $this->init($date, $hour, $minute, $employee, $service = null);
        $this->class = 'fancybox active';
        $this->defaultWorkingTimeClass();
        $this->customTimeClass();
        $this->freeTimeClass();
        $this->bookingClass();
        $employee->setBookedSlot($this->bookedSlot);
        $employee->setFreetimeSlot($this->freetimeSlot);
        $employee->setCustomTimeSlot($this->customTimeSlot);
        return $this->class;
    }

    /**
     * Consider if the current cell is a default working time cell or not
     *
     * @return string
     */
    protected function defaultWorkingTimeClass()
    {
        $class = '';
        $defaultWorkingTime = $this->employee->getDefaulTimesByDayOfWeek($this->dateObj->dayOfWeek);
        $start              = $this->employee->getTodayDefaultStartAt($this->dateObj->dayOfWeek);
        $end                = $this->employee->getTodayDefaultEndAt($this->dateObj->dayOfWeek);

        if ($this->rowTime >= $start && $this->rowTime < $end && !$defaultWorkingTime->is_day_off) {
            $this->class = 'fancybox active';
        } else {
            $this->class = 'inactive';
        }
        return $this->class;
    }

    /**
     * Consider if the current cell is a custom time cell or not
     *
     * @return string
     */
    protected function customTimeClass()
    {
        if(empty($this->customTimeCache)){
            $this->customTimeCache = $this->employee->employeeCustomTimes()
                    ->with('customTime')
                    ->where('date', $this->date)
                    ->get();
        }

        foreach ($this->customTimeCache as $empCustomTime) {
            $start =  $empCustomTime->customTime->getStartAt();
            $end   =  $empCustomTime->customTime->getEndAt();

            if ($this->rowTime >= $start && $this->rowTime < $end && !$empCustomTime->customTime->is_day_off) {
                $this->class = ($this instanceof Backend) ? 'fancybox active' : 'active';
                $this->customTimeSlot[$this->date][$this->hour][$this->minute] = $empCustomTime;
            } else {
                $this->class = 'custom inactive';
            }
        }
        return $this->class;
    }

    /**
     * Consider if the current cell is a freetime cell or not
     *
     * @return string
     */
    protected function freeTimeClass()
    {
        if (empty($this->freetimesCache)) {
            $this->freetimesCache = $this->employee
                ->freetimes()
                ->where('date', $this->date)
                ->get();
        }

        foreach ($this->freetimesCache as $freetime) {

            $start = $freetime->getStartAt();
            $end   = $freetime->getEndAt()->subMinutes(15);//TODO is always 15?

            if ($this->rowTime >= $start && $this->rowTime <= $end) {
                $this->class = 'freetime';
                $this->freetimeSlot[$this->date][$this->hour][$this->minute] = $freetime;
            }
        }
        return $this->class;
    }

    /**
     * Determine class of current cell if there is any booking at that time
     *
     * @return string
     */
    public function bookingClass()
    {
        // get booking only certain date
        if (empty($this->bookingList[$this->date])) {
            $this->bookingList[$this->date] = $this->employee->bookings()
                ->where('date', $this->date)
                ->where('status','!=', Booking::STATUS_CANCELLED)
                ->whereNull('deleted_at')->get();
        }

        foreach ($this->bookingList[$this->date] as $booking) {
            if ($booking->date === $this->date) {
                $subMinutes = ($booking->total % 15 == 0) ? 15 : 0;
                $start      = $booking->getStartAt();
                $end        = $booking->getEndAt()->subMinutes($subMinutes);//15 is duration of single slot

                if(($start->minute % 15) > 0)
                {
                    $complement = ($start->minute % 15);
                    $start->subMinutes($complement);
                }

                if ($this->rowTime >= $start && $this->rowTime <= $end) {
                    $this->class = $booking->getClass();
                    if ($this->rowTime == $start) {
                        $this->class .= ' slot-booked-head';
                    } else {
                        $this->class .= ' slot-booked-body';
                    }
                    $this->bookedSlot[$this->date][$start->hour][$start->minute] = $booking;
                }
            }
        }
        return $this->class;
    }
}
