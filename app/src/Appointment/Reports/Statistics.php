<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;
use Confide;

class Statistics extends Base
{
    /**
     * If not null, generate statistics data of this employee only
     *
     * @var int
     */
    protected $employeeId;

    /**
     * The date passed to report
     *
     * @var Carbon\Carbon
     */
    protected $date;

    /**
     * Start of the month of the given date
     *
     * @var Carbon\Carbon
     */
    protected $start;

    /**
     * End of the month of the given date
     *
     * @var Carbon\Carbon
     */
    protected $end;

    public function __construct(Carbon $date, $employeeId = null)
    {
        $this->date       = $date;
        $this->start      = with(clone $date)->startOfMonth();
        $this->end        = with(clone $date)->endOfMonth();
        $this->employeeId = $employeeId;
    }

    /**
     * @{@inheritdoc}
     */
    protected function fetch()
    {
        $data = [];
        // Prepare keys
        $i = 1;
        while ($i <= $this->date->daysInMonth) {
            $data[$i++] = [];
        }

        // Get revenue of each day
        $data = $this->process($data, $this->getRevenue(), 'revenue');
        $data = $this->process($data, $this->getTotalBookings(), 'bookings');
        $data = $this->process($data, $this->getBookedTime(), 'working_time');
        $data = $this->process($data, $this->getBookedTime(), 'booked_time');

        // Final format
        foreach ($data as &$item) {
            $item['occupation_percent'] = $item['working_time'] > 0
                ? round($item['booked_time'] * 100 / $item['working_time'], 2)
                : 0;
            $item['working_time']       = $this->formatMinutes($item['working_time']);
            $item['booked_time']        = $this->formatMinutes($item['booked_time']);
        }

        return $data;
    }

    /**
     * Helper method to merge raw result into data array, allow to set key
     * and default value
     *
     * @param array  $data    The data to be returned
     * @param array  $result  The result to be merged
     * @param string  $key     Name of key to hold data in $data
     * @param integer $default Default value
     *
     * @return array
     */
    private function process($data, $result, $key, $default = 0)
    {
        foreach ($data as $day => &$item) {
            $item[$key] = isset($result[$day]) ? $result[$day] : $default;
        }
        return $data;
    }

    /**
     * Get all revenues of all days in a month
     *
     * @return array
     */
    protected function getRevenue()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('SUM(total_price) AS revenue'), DB::raw('DAYOFMONTH(created_at) as day'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id)
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        $result = $query->get();
        return array_combine(array_pluck($result, 'day'), array_pluck($result, 'revenue'));
    }

    /**
     * Count all bookings of all days in a month
     *
     * @return array
     */
    protected function getTotalBookings()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('COUNT(id) AS total'), DB::raw('DAYOFMONTH(created_at) as day'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id)
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        $result = $query->get();
        return array_combine(array_pluck($result, 'day'), array_pluck($result, 'total'));
    }

    /**
     * Get total booked time in days
     *
     * @return array
     */
    protected function getBookedTime()
    {
        $query = DB::table('as_bookings')
            ->select(DB::raw('SUM(total) AS booked_time'), DB::raw('DAYOFMONTH(created_at) as day'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->where('user_id', Confide::user()->id)
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        $result = $query->get();
        return array_combine(array_pluck($result, 'day'), array_pluck($result, 'booked_time'));
    }

    // /**
    //  * Get working time of an employee in this month
    //  *
    //  * @param int $employeeId
    //  *
    //  * @return array
    //  */
    // protected function getEmployeeWorkingTime($employeeId)
    // {
    //     // Get custom time first
    //     $custom = DB::table('as_employee_custom_time')
    //         ->where('employee_id', $employeeId)
    //         ->where('user_id', Confide::user()->id)
    //         ->where('date', '>=', $this->start)
    //         ->where('date', '<=', $this->end)
    //         ->get();

    //     if (!empty($custom)) {

    //     }
    // }

    /**
     * Take a number of minutes and format into hh:mm
     *
     * @param int $minutes
     *
     * @return string
     */
    protected function formatMinutes($minutes)
    {
        $hours = floor($minutes / 60);
        $hours = $hours < 10 ? '0'.($hours) : $hours;

        $minutes = $minutes % 60;
        $minutes = $minutes < 10 ? '0'.($minutes) : $minutes;

        return $hours.':'.$minutes;
    }
}
