<?php namespace App\Appointment\Reports;

class MonthlyStatistics extends MonthlyCalendar
{
    /**
     * @{@inheritdoc}
     */
    protected function fetch()
    {
        $data = [];

        $result = $this->doQuery('revenue', false);
        $data['revenue'] = (isset($result->revenue)) ? $result->revenue : 0;

        $result = $this->doQuery('total_bookings', false);
        $data['bookings'] = (isset($result->total_bookings)) ? $result->total_bookings : 0;

        $result = $this->doQuery('booked_time', false);
        $data['booked_time'] = (isset($result->booked_time)) ? $result->booked_time : 0;

        // Working time
        $data['working_time'] = $this->calculateWorkingTime($this->prepareData(), true);

        // Final format
        $data['occupation_percent'] = $data['working_time'] > 0
            ? round($data['booked_time'] * 100 / $data['working_time'], 2)
            : 0;
        $data['booked_time'] = $this->formatMinutes($data['booked_time']);
        $data['working_time'] = $this->formatMinutes($data['working_time']);

        $data['month'] = trans('common.'.strtolower($this->date->format('M')));

        return $data;
    }
}
