<?php namespace App\Appointment\Models;
use Carbon\Carbon;
use Config;

class Base extends \App\Core\Models\Base
{
    //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        $startAt = new Carbon($this->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        $endAt = new Carbon($this->end_at, Config::get('app.timezone'));
        return $endAt;
    }

    /**
     * Get length in minutes
     *
     * return int
     */
    public function getMinutes()
    {
        return (double) $this->getStartAt()->diffInMinutes($this->getEndAt());
    }
}
