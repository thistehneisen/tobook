<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB;
use App\Appointment\Models\NAT\CalendarKeeper;
use App\Appointment\Models\Reception\BackendReceptionist;
use App\Appointment\Models\Employee;
use App\Appointment\Models\CustomTime;
use Carbon\Carbon;

/**
 * Virtual Calendar Planner
 */
class Virtual
{
    public $bookableTimes = [];

    public $timeslots = [];

    /**
     * Get all possible bookable timeslots of all employees with their shortest services
     *
     * @return array
     */
    public function getBookableTimeslots($user, $date)
    {
        foreach ($user->asEmployees as $employee) {
            $this->singleEmployeeTimeslots($user, $employee, $date);
        }

        return $this->timeslots;
    }

    /**
     * Go though the employee calendar and calculate the available slots
     *
     * @return array()
     */
    public function singleEmployeeTimeslots($user, $employee, $date)
    {
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date, true, $employee);
        $service = $employee->shortestService;

        foreach ($workingTimes as $hour => $minutes){
            foreach ($minutes as $minute){
                $this->singleTimeslot($employee, $service, $date, $hour, $minute);
            }
        }

        return $this->timeslots;
    }

    /**
     * Add the timeslot to the list if it is bookable
     *
     * @return void
     */
    public function singleTimeslot($employee, $service, $date, $hour, $minute)
    {
        if (!$this->isActiveSlot($employee, $date, $hour, $minute)) {
            return;
        }

        if ($date->copy()->hour($hour)->minute($minute) < Carbon::now()) {
            return;
        }

        $time = sprintf("%02d:%02d", $hour, $minute);

        if (!isset($this->bookableTimes[$hour][$minute])
            || $this->bookableTimes[$hour][$minute] === false) {

            // if the shortest service is 15, the slot is bookable anyway
            $this->bookableTimes[$hour][$minute] = (intval($service->length) === 15)
                ? true
                : $this->isBookable($employee, $service, $date, $time);

            if ($this->bookableTimes[$hour][$minute]) {
                $this->timeslots[] = $time;
            }
        }

    }

    /**
     * Check if the timeslot is 'active' or not
     *
     * @return boolean
     */
    public function isActiveSlot($employee, $date, $hour, $minute)
    {
        $unbookables = ['inactive','fancybox','booked', 'freetime', 'room', 'resource', 'head', 'body', '_', ' '];
        $slotClass = $employee->getSlotClass($date->toDateString(), $hour, $minute);
        $slotClass = str_replace($unbookables, '', $slotClass);
        return ($slotClass === 'active');
    }

    /**
     * Check if the slot is bookable with certain employee and service
     *
     * @return boolean
     */
    public function isBookable($employee, $service, $date, $time)
    {
        $receptionist = new BackendReceptionist();
        $receptionist->setUser($service->user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($time)
            ->setServiceId($service->id)
            ->setServiceTimeId('default')
            ->setEmployeeId($employee->id);

        return $receptionist->isBookable();
    }
}
