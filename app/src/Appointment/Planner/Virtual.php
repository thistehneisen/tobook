<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB;
use App\Appointment\Models\NAT\CalendarKeeper;
use App\Appointment\Models\Employee;
use App\Appointment\Models\CustomTime;
use Carbon\Carbon;

/**
 * Virtual Calendar Planner
 */
class Virtual
{
    public function getBookableTimeslots($user, $date)
    {
        foreach ($user->asEmployees as $employee) {
            $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date, true, $employee);
            $bookableTimes = [];
            foreach ($workingTimes as $hour => $minutes){
                foreach ($minutes as $minuteShift){
                    $slotClass = $employee->getSlotClass($date->toDateString(), $hour, $minuteShift);
                    $slotClass = str_replace(['inactive', 'fancybox', 'booked', ' '], '', $slotClass);
                    if ($slotClass === 'active') {
                        $bookableTimes[$hour][$minuteShift] = 1;
                    }
                }
            }
            dd($bookableTimes);
        }

    }
}
