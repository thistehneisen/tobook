<?php namespace App\Appointment\Models;
use Carbon\Carbon;
use Config;

class Base extends \App\Core\Models\Base
{
    //Currently don't use attribute because can break other code
    public function getStartAt()
    {
        $startAt =  Carbon::createFromFormat('H:i:s', $this->start_at, Config::get('app.timezone'));
        return $startAt;
    }

    //Currently  don't use attribute because can break other code
    public function getEndAt()
    {
        $endAt =  Carbon::createFromFormat('H:i:s', $this->end_at, Config::get('app.timezone'));
        return $endAt;
    }
}
