<?php namespace App\Appointment\Controllers;

use App, Confide, Util, Hashids;
use App\Core\Models\User;
use App\Appointment\Models\Booking;

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
        list($startHour, $startMinute) = explode(':', $currentWorkingTimes['start']);
        list($endHour, $endMinute)  = explode(':', $currentWorkingTimes['end']);

        //Get the lastest booking end time in current date
        $lastestBooking = Booking::getLastestBookingEndTime($date, $user);
        if(!empty($lastestBooking)){
            $lastestEndTime = $lastestBooking->getEndAt();
            if(($lastestEndTime->hour   >= $endHour)
            && ($lastestEndTime->minute >  $endMinute))
            {
                $endHour   = $lastestEndTime->hour;
                $endMinute = ($lastestEndTime->minute % 15)
                    ? $lastestEndTime->minute + (15 - ($lastestEndTime->minute % 15))
                    : $lastestEndTime->minute;
            }
        }
        $endHour = ($endMinute == 0) ? $endHour - 1 : $endHour;
        $endMinute = ($endMinute == 0 || $endMinute == 60) ? 45 : $endMinute;

        for($i = (int) $startHour; $i<= (int)$endHour; $i++) {
            if($i === (int)$startHour){
                $workingTimes[$i] = range((int)$startMinute, 45, 15);
            }
            if($i !== (int)$startHour && $i !== (int)$endHour)
            {
                $workingTimes[$i] = range(0, 45, 15);
            }
            if($i === (int)$endHour){
                 $workingTimes[$i] = range(0, (int)$endMinute, 15);
            }
        }
        return $workingTimes;
    }
}
