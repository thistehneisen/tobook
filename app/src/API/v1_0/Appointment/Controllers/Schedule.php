<?php namespace App\API\v1_0\Appointment\Controllers;

use Config, Input, Response;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use Carbon\Carbon;

class Schedule extends Base
{
    /**
     * Display schedules of employees of current business owner.
     * Supported query string parameters:
     *  - `date`: date to get schedules for in Y-m-d format, default to today
     *  - `employee_id`: id of employee to get schedules of, default to all employees
     *  - `days`: number of days to get schedules for, default to 1
     *
     * Please note that if schedules for all employees are requested, `days` will be set to 1
     * for performance reason. When only one employee is specified, `days` can be as many as 7.
     *
     * @return Response
     */
    public function getSchedules()
    {
        // get requested date, default to today
        $date = Input::get('date');
        $date = (empty($date)) ? Carbon::today() : $date;
        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }
        $dates = [$date];

        // get requested employee, default to all employees
        $employeeId = Input::get('employee_id');
        if (!empty($employeeId)) {
            // getting appointments for one employee
            $employee = Employee::ofCurrentUser()->find($employeeId);
            if (empty($employee)) {
                return Response::json([
                    'error' => true,
                    'message' => 'Employee not found.',
                ], 404);
            }

            $employees = [$employee];

            // get requested days, default to 1 (max=7)
            $days = max(1, min(7, intval(Input::get('days'))));
            for ($i = 1; $i < $days; $i++) {
                $dates[] = with(clone $date)->addDays($i);
            }
        } else {
            // getting appointments for all employees
            $employees = Employee::ofCurrentUser()->orderBy('order')->get();
        }

        $employeesData = [];
        foreach ($employees as $employee) {
            $employeeData = $this->_prepareEmployeeData($employee);
            $employeeData['schedules'] = array();

            foreach ($dates as $date) {
                $workingTimes = $employee->getDefaultWorkingTimes($date);
                $dateStr = $date->toDateString();

                $employeeData['schedules'][$dateStr] = [];
                $lastFreetimeId = 0;
                $lastBookingId = 0;
                for ($hour = 0; $hour < 24; $hour++) {
                    for ($minute = 0; $minute < 59; $minute += 15) {
                        $slotClass = $employee->getSlotClass($dateStr, $hour, $minute);
                        if (strpos($slotClass, 'inactive') !== false) {
                            // the time slot is inactive
                            continue;
                        }

                        $freetime = $employee->getFreetime($dateStr, $hour, $minute);
                        if (!empty($freetime)) {
                            if ($freetime->id != $lastFreetimeId) {
                                $freetimeData = $this->_prepareFreetimeData($freetime);
                                $employeeData['schedules'][$dateStr][] = $freetimeData;
                                $lastFreetimeId = $freetime->id;
                            }

                            // still within the previous freetime block
                            continue;
                        }

                        $booking = $employee->getBooked($dateStr, $hour, $minute);
                        if (!empty($booking)) {
                            if ($booking->id != $lastBookingId) {
                                $bookingData = $this->_prepareBookingData($booking);
                                $employeeData['schedules'][$dateStr][] = $bookingData;
                                $lastBookingId = $booking->id;
                            }

                            // still within the previous booking block
                            continue;
                        }

                        $activeData = $this->_prepareActiveData($date, $hour, $minute);
                        $employeeData['schedules'][$dateStr][] = $activeData;
                    }
                }
            }

            $employeesData[] = $employeeData;
        }

        return Response::json([
            'error' => false,
            'employees' => $employeesData,
        ]);
    }
}
