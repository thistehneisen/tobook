<?php namespace App\Appointment\Controllers;

use View, Input;
use App\Core\Controllers\Base;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Models\ServiceCategory;
use Carbon\Carbon;
class Index extends Base
{
    /**
     * Show booking calendar
     *
     * @return View
     */
    public function index($date = null)
    {
        $employees = Employee::ofCurrentUser()->get();
        $workingTimes = range(8,17);
        $date = (empty($date)) ? Carbon::today() : $date;

        if(!$date instanceof Carbon){
            try{
                $date = Carbon::createFromFormat('Y-m-d', $date);
            } catch(\Exception $ex){
                $date = Carbon::today();
            }
        }

        $selectedDate = $date->toDateString();

        return View::make('modules.as.index.index', [
                'employees'    => $employees,
                'workingTimes' => $workingTimes,
                'selectedDate' => $selectedDate,
                'dayOfWeek'    => $date->dayOfWeek
            ]);
    }
}
