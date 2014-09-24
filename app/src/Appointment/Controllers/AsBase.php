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
        $workingTimes = $user->getASDefaultWorkingTimes($date);
        return $workingTimes;
    }
}
