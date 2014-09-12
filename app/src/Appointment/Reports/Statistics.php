<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;

class Statistics extends Base
{
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

    public function __construct(Carbon $date)
    {
        $this->date  = $date;
        $this->start = with(clone $date)->startOfMonth();
        $this->end   = with(clone $date)->endOfMonth();
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

        return $data;
    }

    protected function process($data, $result, $key, $default = 0)
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
        $result = DB::table('as_bookings')
            ->select(DB::raw('SUM(total_price) AS revenue'), DB::raw('DAYOFMONTH(created_at) as day'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        return array_combine(array_pluck($result, 'day'), array_pluck($result, 'revenue'));
    }

    /**
     * Count all bookings of all days in a month
     *
     * @return array
     */
    protected function getTotalBookings()
    {
        $result = DB::table('as_bookings')
            ->select(DB::raw('COUNT(id) AS total'), DB::raw('DAYOFMONTH(created_at) as day'))
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        return array_combine(array_pluck($result, 'day'), array_pluck($result, 'total'));
    }
}
