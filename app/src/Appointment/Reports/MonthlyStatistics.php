<?php namespace App\Appointment\Reports;

use Carbon\Carbon;
use DB;
use Confide;

class MonthlyStatistics extends Statistics
{
    /**
     * @{@inheritdoc}
     */
    public function fetch()
    {
        $data = $this->prepareData();
    }
}
