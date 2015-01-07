<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;
use Confide;

class MonthlyStatistics extends MonthlyReport
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

        // Working time
        $data['working_time'] = $this->calculateWorkingTime($this->prepareData());

        // Final format
        $data['occupation_percent'] = $data['working_time'] > 0
            ? round($data['booked_time'] * 100 / $data['working_time'], 2)
            : 0;
        $data['booked_time'] = $this->formatMinutes($data['booked_time']);
        $data['working_time'] = $this->formatMinutes($data['working_time']);

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

    protected function calculateWorkingTime($data)
    {
        list($custom, $default, $freetime) = $this->getWorkingTime();

        $total = 0;
        $map = [
            Carbon::MONDAY    => 'mon',
            Carbon::TUESDAY   => 'tue',
            Carbon::WEDNESDAY => 'wed',
            Carbon::THURSDAY  => 'thu',
            Carbon::FRIDAY    => 'fri',
            Carbon::SATURDAY  => 'sat',
            Carbon::SUNDAY    => 'sun',
        ];

        foreach ($data as $day => $item) {

            if (isset($custom[$day])) {
                // If this day has a custom working time
                $total += (int) $custom[$day];
            } else {
                // If not, we'll use the default working time
                $dayOfWeek = $item['date']->dayOfWeek;
                if (isset($default[$map[$dayOfWeek]])) {
                    $total += (int) $default[$map[$dayOfWeek]];
                }
            }

            // Subtract the free time
            if ($total > 0 && isset($freetime[$day])) {
                $total -= $freetime[$day];
            }
        }

        return $total;
    }
}
