<?php namespace App\Appointment\Reports;

use DB;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee as EmployeeModel;

class Employee extends Base
{
    protected $cache;

    public function toJson()
    {
        $data = $this->get();
        $json = [];
        foreach ($data as $item) {
            $json[] = [
                'employee'  => $item['employee']->name,
                'total'     => isset($item['total']) ? $item['total'] : 0,
                'confirmed' => isset($item['confirmed']) ? $item['confirmed'] : 0,
                'pending'   => isset($item['pending']) ? $item['pending'] : 0,
                'cancelled' => isset($item['cancelled']) ? $item['cancelled'] : 0,
            ];
        }
        return json_encode($json);
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

        $ret = [];
        $employees = $this->getEmployees();
        foreach ($employees as $employee) {
            $item = $data[$employee->id];
            $item['employee'] = $employee;
            $ret[] = $item;
        }

        $this->cache = $ret;

        return $ret;
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
