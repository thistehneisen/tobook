<?php namespace App\Appointment\Reports;

use DB;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee as EmployeeModel;

class Employee
{
    protected $cache;

    public function get()
    {
        return ($this->cache !== null) ? $this->cache : $this->fetch();
    }

    public function toJson()
    {
        $data = $this->get();
    }

    protected function fetch()
    {
        $data = [];
        $result = $this->countTotalBookings();
        foreach ($result as $item) {
            $data[$item->employee_id]['total'] = $item->total;
        }

        $result = $this->countBookingsByStatus();
        foreach ($result as $item) {
            $status = Booking::getStatusByValue($item->status);
            $data[$item->employee_id][$status] = $item->total;
        }

        $employees = $this->getEmployees();
        foreach ($data as $emplyeeId => &$item) {
            $item['employee'] = $employees[$emplyeeId];
        }

        $this->cache = $data;

        return $data;
    }

    protected function countTotalBookings()
    {
        return Booking::select(DB::raw('COUNT(id) AS total'), 'employee_id')
            ->ofCurrentUser()
            ->groupBy('employee_id')
            ->get();
    }

    protected function countBookingsByStatus()
    {
        return Booking::select(DB::raw('COUNT(id) AS total'), 'employee_id', 'status')
            ->ofCurrentUser()
            ->groupBy('employee_id', 'status')
            ->get();
    }

    protected function getEmployees()
    {
        $all = EmployeeModel::ofCurrentUser()->get();
        $data = [];
        $all->each(function ($item) use (&$data) {
            $data[$item->id] = $item;
        });

        return $data;
    }
}
