<?php namespace App\Appointment\Controllers;

use View;
use App\Core\Controllers\Base;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Models\ServiceCategory;
class Index extends Base
{
    /**
     * Show booking calendar
     *
     * @return View
     */
    public function index()
    {
        $employees = Employee::ofCurrentUser()->get();
        $workingTimes = range(8,17);
        $bookingStatuses = Booking::getStatuses();
        return View::make('modules.as.index.index', [
                'employees' => $employees,
                'workingTimes' => $workingTimes,
                'bookingStatuses' => $bookingStatuses
            ]);
    }
}
