<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\EmployeeFreeTime;

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
    private $resourceCache   = [];
    private $roomCache       = [];


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
        $this->init($date, $hour, $minute, $employee, $service);
        $this->class = 'fancybox active';
        $this->defaultWorkingTimeClass();
        $this->customTimeClass();
        $this->freeTimeClass();
        $this->bookingClass();
        $this->resourceClass();
        $this->roomClass();
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
        $defaultWorkingTime = $this->employee->getDefaulTimesByDayOfWeek($this->dateObj->dayOfWeek);
        $start              = $this->employee->getTodayDefaultStartAt($this->dateObj->dayOfWeek);
        $end                = $this->employee->getTodayDefaultEndAt($this->dateObj->dayOfWeek);

        if ($this->rowTime >= $start && $this->rowTime < $end && !$defaultWorkingTime->is_day_off) {
            $this->class = $this->getValue('active');
        } else {
            $this->class = $this->getValue('inactive');
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
            if(empty($empCustomTime->customTime)) {
                continue;
            }
            $start =  $empCustomTime->customTime->getStartAt();
            $end   =  $empCustomTime->customTime->getEndAt();

            if ($this->rowTime >= $start && $this->rowTime < $end && !$empCustomTime->customTime->is_day_off) {
                $this->class = $this->getValue('custom_active');
                $this->customTimeSlot[$this->date][$this->hour][$this->minute] = $empCustomTime;
            } else {
                $this->class = $this->getValue('custom_inactive');
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
                if ($this->rowTime == $start) {
                    $this->class .= ($freetime->type === EmployeeFreeTime::PERSONAL_FREETIME)
                        ? $this->getValue('freetime_head')
                        : $this->getValue('freetime_working_head');
                } else {
                    $this->class .= ($freetime->type === EmployeeFreeTime::PERSONAL_FREETIME)
                        ? $this->getValue('freetime_body')
                        : $this->getValue('freetime_working_body');
                }
                $this->freetimeSlot[$this->date][$this->hour][$this->minute] = $freetime;
                $this->class = trim(str_replace('fancybox active', '', $this->class));
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
                $subMinutes = 15;//15 is duration of single slot
                $start      = $booking->getStartAt();
                $end        = $booking->getEndAt()->subMinutes($subMinutes);

                if(($start->minute % 15) > 0)
                {
                    $complement = $start->minute % 15;
                    $start->subMinutes($complement);
                }

                if(($end->minute % 15) > 0)
                {
                    $complement = 15 - ($end->minute % 15);
                    $end->addMinutes($complement);
                }

                if ($this->rowTime >= $start && $this->rowTime <= $end) {
                    $this->class = (!($this instanceof Next)) ? $booking->getClass() : '';
                    if ($this->rowTime == $start) {
                        $this->class .= $this->getValue('booked_head');
                    } else {
                        $this->class .= $this->getValue('booked_body');
                    }
                    $this->class .= sprintf(' booking-id-%d', $booking->id);
                    $this->bookedSlot[$this->date][$this->rowTime->hour][$this->rowTime->minute] = $booking;
                }
            }
        }

        return $this->class;
    }

    public function resourceClass()
    {
        $resourceIds = [];
        if (empty($this->service)) {
            return $this->class;
        }

        $resourceIds = $this->service->resources->lists('id');
        if(empty($resourceIds)) {
            return $this->class;
        }

        if(!isset($this->resourceCache[$this->date][$this->service->id]['query'])) {
            $query = Booking::where('as_bookings.date', $this->date)
                    ->whereNull('as_bookings.deleted_at')
                    ->where('as_bookings.status','!=', Booking::STATUS_CANCELLED)
                    ->join('as_booking_services', 'as_booking_services.booking_id', '=','as_bookings.id')
                    ->join('as_services', 'as_services.id', '=','as_booking_services.service_id')
                    ->join('as_resource_service', 'as_resource_service.service_id', '=', 'as_services.id')
                    ->whereIn('as_resource_service.resource_id', $resourceIds)->get();
            $this->resourceCache[$this->date][$this->service->id]['query'] = $query;
        }

        $this->resourceCache[$this->date][$this->service->id][$this->hour][$this->minute] = true;

        foreach ($this->resourceCache[$this->date][$this->service->id]['query'] as $booking) {
            $start = $booking->getStartAt();
            $end   = $booking->getEndAt();
            if ($this->rowTime >= $start && $this->rowTime < $end) {
                $this->resourceCache[$this->date][$this->service->id][$this->hour][$this->minute] = false;
            }
        }

        if(!$this->resourceCache[$this->date][$this->service->id][$this->hour][$this->minute]) {
            $this->class = $this->getValue('resource_inactive');
        }

        return $this->class;
    }

    public function roomClass()
    {
        $roomIds = [];
        if (empty($this->service)) {
            return $this->class;
        }

        $roomIds = $this->service->rooms->lists('id');
        $totalRooms = count($roomIds);

        if (empty($roomIds)) {
            return $this->class;
        }

        if (!isset($this->resourceCache[$this->date][$this->service->id]['query'])) {
            $query = Booking::where('as_bookings.date', $this->date)
                    ->whereNull('as_bookings.deleted_at')
                    ->where('as_bookings.status','!=', Booking::STATUS_CANCELLED)
                    ->join('as_booking_services', 'as_booking_services.booking_id', '=','as_bookings.id')
                    ->join('as_booking_service_rooms', 'as_booking_service_rooms.booking_service_id', '=', 'as_booking_services.id')
                    ->whereIn('as_booking_service_rooms.room_id', $roomIds)->get();
            $this->roomCache[$this->date][$this->service->id]['query'] = $query;
        }

        $this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute] = true;

        if (isset($this->roomCache[$this->date][$this->service->id]['query'])) {
            foreach ($this->roomCache[$this->date][$this->service->id]['query'] as $booking) {
                $start = $booking->getStartAt();
                $end   = $booking->getEndAt();
                if ($this->rowTime >= $start && $this->rowTime <= $end) {
                    if (!isset($this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute])) {
                        $this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute] = $totalRooms;
                    }
                    if ($this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute] > 0) {
                        $this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute] -= 1;
                    }

                }
            }
        }

        if (!$this->roomCache[$this->date][$this->service->id][$this->hour][$this->minute]) {
            $this->class = $this->getValue('room_inactive');
        }

        return $this->class;
    }

    protected function getValue($key)
    {
        $map = [
            'active'                => 'fancybox active',
            'inactive'              => 'fancybox inactive',
            'freetime_head'         => 'freetime freetime-head',
            'freetime_body'         => 'freetime freetime-body',
            'freetime_working_head' => ' freetime freetime-head freetime-working',
            'freetime_working_body' => ' freetime freetime-body freetime-working',
            'custom_active'         => 'custom fancybox active',
            'custom_inactive'       => 'custom fancybox inactive',
            'resource_inactive'     => 'resource fancybox inactive',
            'room_inactive'         => 'room fancybox inactive',
            'booked_head'           => ' slot-booked-head',
            'booked_body'           => ' slot-booked-body',
        ];

        return (isset($map[$key])) ? $map[$key] : '';
    }
}
