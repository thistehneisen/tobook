<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB, NAT;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Service;
use App\Appointment\Models\CustomTime;

class Freetime
{


    /**
     * Generate possible workshift from 6:00 to 22:45
     * @return array
     */
    public function getWorkshift()
    {
        //TODO get form settings or somewhere else
        $workingTimes = range(6, 22);
        $workShift = range(0, 45, 15);
        $times = [];
        foreach ($workingTimes as $hour) {
           foreach (range(0, 45, 15) as $minuteShift) {
                $time = sprintf('%02d:%02d', $hour, $minuteShift);
                $times[$time] = $time;
           }
        }
        return $times;
    }
}
