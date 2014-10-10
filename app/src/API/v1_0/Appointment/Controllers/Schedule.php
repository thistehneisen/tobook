<?php namespace App\API\v1_0\Appointment\Controllers;

use Config, Input, Response;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Core\Controllers\Base as Base;
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

    protected function _prepareEmployeeData($employee)
    {
        $employeeData = [
            'type' => 'employee',
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
        ];

        return $employeeData;
    }

    protected function _prepareFreetimeData($freetime)
    {
        $duration = $this->_calculateDuration($freetime->getStartAt(), $freetime->getEndAt());

        $freetimeData = [
            'type' => 'freetime',
            'freetime_id' => $freetime->id,
            'freetime_description' => $freetime->description,

            'date' => $freetime->date,
            'start_at' => $freetime->start_at,
            'end_at' => $freetime->end_at,
            'duration' => $duration,
        ];

        return $freetimeData;
    }

    protected function _prepareBookingData($booking)
    {
        $service = $booking->bookingServices()->first();
        $services = !empty($service->service->name)
            ? [$service->service->name]
            : [];
        $services = array_merge($services, $booking->getExtraServices());

        $consumerName = $booking->consumer->getNameAttribute();

        $duration = $this->_calculateDuration($booking->getStartAt(), $booking->getEndAt());

        $bookingData = [
            'type' => 'booking',
            'booking_id' => $booking->id,
            'booking_uuid' => $booking->uuid,
            'booking_services' => $services,
            'booking_notes' => $booking->notes,
            'consumer_name' => $consumerName,

            'date' => $booking->date,
            'start_at' => $booking->start_at,
            'end_at' => $booking->end_at,
            'duration' => $duration,
        ];

        return $bookingData;
    }

    protected function _prepareActiveData(Carbon $date, $hour, $minute)
    {
        // TODO: is it always 15 minutes?!
        $duration = 15;

        $activeStartAt = Carbon::createFromTime($hour, $minute, 0, Config::get('app.timezone'));

        $activeData = [
            'type' => 'active',
            'date' => $date->toDateString(),
            'start_at' => $activeStartAt->toTimeString(),
            'end_at' => $activeStartAt->addMinutes($duration)->toTimeString(),
            'duration' => $duration,
        ];

        return $activeData;
    }

    protected function _calculateDuration(Carbon $startAt, Carbon $endAt)
    {
        $diff = $endAt->diff($startAt);
        return ($diff->h * 60 + $diff->i);
    }
}
