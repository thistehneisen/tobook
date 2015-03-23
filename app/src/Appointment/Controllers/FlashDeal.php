<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, NAT, Closure;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\Option;

class FlashDeal extends AsBase
{
    public function getFlashDealForm()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');

        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services;

        $master_categories = MasterCategory::lists('name','id');

        return View::make('modules.as.flashdeal.form', [
            'employee'        => $employee,
            'categories'      => $master_categories,
            'bookingDate'     => $bookingDate,
            'startTime'       => $startTime
        ]);
    }
}
