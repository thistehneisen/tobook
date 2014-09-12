<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;

class Backend implements Strategy
{
    /**
     * These variables to use as a dictionary to easy to get back
     *  and limit access to db in for loop
     */
    private $bookedSlot     = [];
    private $bookingList    = [];
    private $freetimeSlot   = [];
    private $freetimesCache = [];

    public function determineClass($date, $hour, $minute, $employee, $service = null){
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        $class = 'inactive';
        //working time
        $rowTime = Carbon::createFromTime($hour, $minute, 0, Config::get('app.timezone'));
        list($startHour, $startMinute) = explode(':', $employee->getTodayDefaultStartAt($selectedDate->dayOfWeek));
        $startAt =  Carbon::createFromTime($startHour, $startMinute, 0, Config::get('app.timezone'));
        list($endHour, $endMinute) = explode(':', $employee->getTodayDefaultEndAt($selectedDate->dayOfWeek));
        $endAt = Carbon::createFromTime($endHour, $endMinute, 0, Config::get('app.timezone'));

        if ($rowTime >= $startAt && $rowTime <= $endAt) {
            $class = 'fancybox active';
        } else {
            $class = 'inactive';
        }

        if(empty($this->freetimesCache)){
            $this->freetimesCache = $employee->freetimes()->where('date', $selectedDate->toDateString())->get();
        }

        foreach ($this->freetimesCache as $freetime) {
            $startAt =  Carbon::createFromFormat('H:i:s', $freetime->start_at, Config::get('app.timezone'));
            $endAt   =  Carbon::createFromFormat('H:i:s', $freetime->end_at, Config::get('app.timezone'));
            if ($rowTime >= $startAt && $rowTime <= $endAt) {
                $class = 'freetime';
                $this->freetimeSlot[$selectedDate->toDateString()][(int) $hour][(int) $minute] = $freetime;
            }
        }

        // get booking only certain date
        if (empty($this->bookingList[$selectedDate->toDateString()])) {
            $this->bookingList[$selectedDate->toDateString()] = $employee->bookings()->where('date', $selectedDate->toDateString())->whereNull('deleted_at')->get();
        }
        foreach ($this->bookingList[$selectedDate->toDateString()] as $booking) {
            $bookingDate =  Carbon::createFromFormat('Y-m-d', $booking->date);
            if ($bookingDate == $selectedDate) {
                list($startHour, $startMinute, $startSecond) = explode(':', $booking->start_at);
                $subMinutes     = ($booking->total % 15 == 0) ? 15 : 10;
                $bookingStartAt =  Carbon::createFromTime($startHour, $startMinute, 0, Config::get('app.timezone'));
                $bookingEndAt   =  with(clone $bookingStartAt)->addMinutes($booking->total)->subMinutes($subMinutes);//15 is duration of single slot
                if ($rowTime >= $bookingStartAt && $rowTime <= $bookingEndAt) {
                    $class = $booking->getClass();
                    if ($rowTime == $bookingStartAt) {
                        $class .= ' slot-booked-head';
                    } else {
                        $class .= ' slot-booked-body';
                    }

                    $this->bookedSlot[$selectedDate->toDateString()][(int) $startHour][(int) $startMinute] = $booking;
                }
            }
        }

        $employee->setBookedSlot($this->bookedSlot);
        $employee->setFreetimeSlot($this->freetimeSlot);

        return $class;
    }
}
