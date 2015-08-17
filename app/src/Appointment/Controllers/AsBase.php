<?php namespace App\Appointment\Controllers;

use App, Confide, Util, Hashids, Input, Validator;
use App\Core\Models\User;
use App\Appointment\Models\NAT\CalendarKeeper;

class AsBase extends \App\Core\Controllers\Base
{
    use App\Appointment\Controllers\Embed\Layout;

    public function __construct()
    {
        parent::__construct();
    }

    public function getDefaultWorkingTimes($date, $hash = null, $employee = null)
    {
        if (!empty($this->user)) {
            $user = $this->user;
        } else {
            $decoded = Hashids::decrypt($hash);
            $user = User::find($decoded[0]);
        }
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date, true, $employee);

        return $workingTimes;
    }
}
