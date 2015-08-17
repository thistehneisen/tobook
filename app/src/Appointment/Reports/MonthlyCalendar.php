<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;
use Confide;
use App\Appointment\Models\Employee;

class MonthlyCalendar extends Base
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
        $data = $this->prepareData();

        // Get revenue of each day
        $data = $this->process($data, $this->doQuery('revenue'), 'revenue');
        $data = $this->process($data, $this->doQuery('total_bookings'), 'bookings');
        $data = $this->process($data, $this->doQuery('booked_time'), 'booked_time');
        $data = $this->calculateWorkingTime($data);

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
     * Prepare an array having the same items as number of days in this month
     *
     * @return array
     */
    protected function prepareData()
    {
        $data = [];
        // Prepare keys
        $i = 1;
        $date = clone $this->start;
        while ($i <= $this->date->daysInMonth) {
            $data[$i++] = ['date' => clone $date];
            $date->addDay();
        }

        return $data;
    }

    /**
     * Helper method to merge raw result into data array, allow to set key
     * and default value
     *
     * @param array   $data    The data to be returned
     * @param array   $result  The result to be merged
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

    protected function doQuery($type, $groupByDate = true)
    {
        $query = DB::table('as_bookings')
            ->where('date', '>=', $this->start)
            ->where('date', '<=', $this->end)
            ->where('user_id', Confide::user()->id)
            ->where('status', '!=', 3)
            ->whereNull('deleted_at');

        $select = '';
        switch ($type) {
            case 'revenue':
                $select = DB::raw('SUM(total_price) AS revenue');
                break;

            case 'total_bookings':
                $select = DB::raw('COUNT(id) AS total_bookings');
                break;

            case 'booked_time':
                $select = DB::raw('SUM(total) AS booked_time');
                break;

            default:
                break;
        }

        if ($this->employeeId !== null) {
            $query = $query->where('employee_id', $this->employeeId);
        }

        if ($groupByDate) {
            $result = $query->groupBy('date')
                ->select($select, DB::raw('DAYOFMONTH(date) as day'))
                ->get();

            return array_combine(array_pluck($result, 'day'), array_pluck($result, $type));
        } else {
            return $query->select($select)->first();
        }
    }

    /**
     * Get working time of an employee in this month
     *
     * @return array
     */
    protected function getWorkingTime()
    {
        $employeeId = [$this->employeeId];

        if ($this->employeeId === null) {
            $employeeId = Employee::ofCurrentUser()->select('id')->get()->lists('id');
        }

        $custom = [];
        // Get custom time first
        $result = DB::table('as_employee_custom_time')
            ->join('as_custom_times', 'as_custom_times.id','=','as_employee_custom_time.custom_time_id')
            ->whereIn('as_employee_custom_time.employee_id', $employeeId)
            ->where('as_employee_custom_time.date', '>=', $this->start)
            ->where('as_employee_custom_time.date', '<=', $this->end)
            ->select(
                DB::raw('HOUR(SUBTIME(varaa_as_custom_times.end_at, varaa_as_custom_times.start_at)) * 60 + MINUTE(SUBTIME(varaa_as_custom_times.end_at, varaa_as_custom_times.start_at)) AS minutes'),
                DB::raw('DAYOFMONTH(varaa_as_employee_custom_time.date) AS day'),
                'as_custom_times.is_day_off',
                'as_employee_custom_time.employee_id'
            )
            ->get();

        if (!empty($result)) {
            foreach ($result as $item) {
                $custom[$item->employee_id][$item->day] = 0;
                $custom[$item->employee_id][$item->day] += $item->minutes;
            }
        }

        // Get default time
        $default = [];
        $result = DB::table('as_employee_default_time')
            ->whereIn('employee_id', $employeeId)
            ->select(
                'as_employee_default_time.*',
                DB::raw('HOUR(SUBTIME(end_at, start_at)) * 60 + MINUTE(SUBTIME(end_at, start_at)) AS minutes')
            )
            ->get();
        if (!empty($result)) {
            foreach ($result as $item) {
                $default[$item->employee_id][$item->type] = 0;
                $default[$item->employee_id][$item->type] += $item->minutes;
            }
        }

        // Get freetime
        $freetime = [];
        $result = DB::table('as_employee_freetime')
            ->whereIn('employee_id', $employeeId)
            ->where('date', '>=', $this->start)
            ->where('date', '<=', $this->end)
            ->select(
                DB::raw('HOUR(SUBTIME(end_at, start_at)) * 60 + MINUTE(SUBTIME(end_at, start_at)) AS minutes'),
                DB::raw('DAYOFMONTH(date) AS day'),
                'employee_id'
            )
            ->get();

        if (!empty($result)) {
            foreach ($result as $item) {
                $freetime[$item->employee_id][$item->day] = 0;
                $freetime[$item->employee_id][$item->day] += $item->minutes;
            }
        }

        return [$custom, $default, $freetime];
    }

    protected function calculateWorkingTime($data, $returnTotal = false)
    {
        list($custom, $default, $freetime) = $this->getWorkingTime();

        $totalWorkingTime = 0;
        $map = [
            Carbon::MONDAY    => 'mon',
            Carbon::TUESDAY   => 'tue',
            Carbon::WEDNESDAY => 'wed',
            Carbon::THURSDAY  => 'thu',
            Carbon::FRIDAY    => 'fri',
            Carbon::SATURDAY  => 'sat',
            Carbon::SUNDAY    => 'sun',
        ];

        $employeeIds = array_keys($default);
        foreach ($data as $day => &$item) {
            foreach ($employeeIds as $employeeId) {
                $total = 0;

                if (isset($custom[$employeeId][$day])) {
                    // If this day has a custom working time
                    $total += (int) $custom[$employeeId][$day];
                } else {
                    // If not, we'll use the default working time
                    $dayOfWeek = $item['date']->dayOfWeek;
                    if (isset($default[$employeeId][$map[$dayOfWeek]])) {
                        $total += (int) $default[$employeeId][$map[$dayOfWeek]];
                    }
                }

                // Subtract the free time
                if ($total > 0 && isset($freetime[$employeeId][$day])) {
                    $total -= $freetime[$employeeId][$day];
                }

                $item['working_time'][$employeeId] = $total;

                $totalWorkingTime += $total;
            }

            $item['working_time'] = ($this->employeeId === null)
                ? (isset($item['working_time'])
                    ? array_sum($item['working_time'])
                    : 0)
                : $item['working_time'][$this->employeeId];
        }

        if ($returnTotal) {
            return $totalWorkingTime;
        } else {
            return $data;
        }

    }

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
