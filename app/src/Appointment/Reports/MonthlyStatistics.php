<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;
use Confide;

class MonthlyStatistics extends Statistics
{
    /**
     * @{@inheritdoc}
     */
    protected function fetch()
    {
        $data = [];

        $result = $this->getRevenue();
        $data['revenue'] = (isset($result->revenue)) ? $result->revenue : 0;

        $result = $this->getTotalBookings();
        $data['bookings'] = (isset($result->total)) ? $result->total : 0;

        $result = $this->getBookedTime();
        $data['booked_time'] = (isset($result->booked_time)) ? $result->booked_time : 0;

        // Final format
        $data['booked_time'] = $this->formatMinutes($data['booked_time']);

        $data['month'] = trans('common.'.strtolower($this->date->format('M')));
        return $data;
    }

    /**
     * @{@inheritdoc}
     */
    protected function getRevenue()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('SUM(total_price) AS revenue'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id);

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        return $query->first();
    }

    /**
     * @{@inheritdoc}
     */
    protected function getTotalBookings()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('COUNT(id) AS total'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id);

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        return $query->first();
    }

    /**
     * @{@inheritdoc}
     */
    protected function getBookedTime()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('SUM(total) AS booked_time'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id);

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        return $query->first();
    }
}
