<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB, NAT;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\Booking;

class Freetime
{
    public $employeeIds;
    public $freetimeId;
    public $startAt;
    public $endAt;
    public $dateRange;
    public $user;
    public $description;
    public $type;

    public function fill($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        if ($this->startAt->gt($this->endAt) || $this->startAt->eq($this->endAt)) {
            throw new \Exception(trans('as.employees.error.start_time_greater_than_end_time'), 1);
        }

        if (empty($this->dateRange)) {
            throw new \Exception(trans('as.employees.error.invalid_date_range'), 1);
        }
    }

    /**
     * Find all overlapped bookings
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getOverlappedBookings()
    {
        $bookings = array();
        $dates = explode(',', $this->dateRange);
        foreach ($dates as $date) {
            foreach ($this->employeeIds as $employeeId) {
                $overlaps = Booking::getOverlappedBookings($employeeId, $date, $this->startAt, $this->endAt);
                foreach ($overlaps as $booking) {
                    $bookings[] = $booking;
                }
            }
        }
       return $bookings;
    }

    /**
     * Find all overlapped free times
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getOverlappedFreetimes()
    {
        $freetimes = array();
        for ($day = 0; $day < $this->days; $day++) {
            $date = $this->fromDate->copy()->addDays($day);
            foreach ($this->employeeIds as $employeeId) {
                $overlaps = EmployeeFreetime::getOverlappedFreetimes($employeeId, $date, $this->startAt, $this->endAt, $this->freetimeId);
                foreach ($overlaps as $booking) {
                    $freetimes[] = $booking;
                }
            }
        }
       return $freetimes;
    }

    public function validateData()
    {
        $bookings = $this->getOverlappedBookings();
        $data = [];
        //Checking if freetime overlaps with any booking or not
        if (!empty($bookings)) {
            $data['success'] = false;
            $data['message'] = trans('as.employees.error.freetime_overlapped_with_booking');
            $data['message'] .= '<ul>';
            foreach ($bookings as $booking) {
                $data['message'] .= '<li>' . $booking->startTime->toDateTimeString() . '</li>';
            }
            $data['message'] .= '</ul>';

            return $data;
        }

        $freetimes = $this->getOverlappedFreetimes();

        if (!empty($freetimes)) {
            $data['success'] = false;
            $data['message'] = trans('as.employees.error.freetime_overlapped_with_others');
            $data['message'] .= '<ul>';
            foreach ($freetimes as $freetime) {
                $data['message'] .= '<li>' . $freetime->startTime->toDateTimeString() . '</li>';
            }
            $data['message'] .= '</ul>';

            return $data;
        }
    }

    public function saveFreetimes()
    {
        $data = [];
        for ($day = 0; $day < $this->days; $day++) {
            foreach ($this->employeeIds as $employeeId) {
                $employeeFreetime = new EmployeeFreetime();
                $date = $this->fromDate->copy()->addDays($day);

                $employeeFreetime->fill([
                    'date'        => $date->toDateString(),
                    'start_at'    => $this->startAt->toTimeString(),
                    'end_at'      => $this->endAt->toTimeString(),
                    'description' => $this->description,
                    'type'        => $this->type
                ]);

                $employee = Employee::ofCurrentUser()->find($employeeId);
                $employeeFreetime->user()->associate($this->user);
                $employeeFreetime->employee()->associate($employee);
                $employeeFreetime->save();
                $data['success'] = true;
                // Remove NAT slots since employee has freetime
                NAT::removeEmployeeFreeTime($employeeFreetime);
            }
        }

        return $data;
    }

    public function editEmployeeFreetime($employeeFreetime)
    {
        $employeeFreetime->fill([
            'start_at'    => $this->startAt->toTimeString(),
            'end_at'      => $this->endAt->toTimeString(),
            'description' => $this->description,
            'type'        => $this->type
        ]);

        return $employeeFreetime->save();
    }

    /**
     * Generate possible workshift from 6:00 to 22:45
     *
     * @return array
     */
    public function getWorkshift()
    {
        //TODO get form settings or somewhere else
        $times        = [];
        $workingTimes = range(6, 22);
        $workShift    = range(0, 45, 15);
        foreach ($workingTimes as $hour) {
           foreach (range(0, 45, 15) as $minuteShift) {
                $time = sprintf('%02d:%02d', $hour, $minuteShift);
                $times[$time] = $time;
           }
        }

        return $times;
    }

}
