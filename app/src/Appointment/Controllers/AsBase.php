<?php namespace App\Appointment\Controllers;

use App, Confide, Util, Hashids;
use App\Core\Models\User;

class AsBase extends \App\Core\Controllers\Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultWorkingTimes($date, $hash = null)
    {
        if(!empty($this->user)){
            $user = $this->user;
        } else {
            $decoded = Hashids::decrypt($hash);
            $user = User::find($decoded[0]);
        }
        $settingsWorkingTime = $user->asOptions->get('working_time');
        $workingTimes = [];
        $currentWeekDay = Util::getDayOfWeekText($date->dayOfWeek);
        $currentWorkingTimes = $settingsWorkingTime[$currentWeekDay];
        list($start_hour, $start_minute) = explode(':', $currentWorkingTimes['start']);
        list($end_hour, $end_minute)  = explode(':', $currentWorkingTimes['end']);

        $end_hour = ((int)$end_minute == 0) ? $end_hour - 1 : $end_hour;
        $end_minute = ((int)$end_minute == 0) ? 45 : $end_minute;
        for($i = (int) $start_hour; $i<= (int)$end_hour; $i++) {
            if($i === (int)$start_hour){
                $workingTimes[$i] = range((int)$start_minute, 45, 15);
            }
            if($i !== (int)$start_hour && $i !== (int)$end_hour)
            {
                $workingTimes[$i] = range(0, 45, 15);
            }
            if($i === (int)$end_hour){
                 $workingTimes[$i] = range(0, (int)$end_minute, 15);
            }
        }
        return $workingTimes;
    }
}
