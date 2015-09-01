<?php namespace App\Core\Controllers\Ajax;

use DB, Request, View;
use Confide, Redirect, Config, Input;
use Illuminate\Support\Collection;
use App\Core\Models\User as Business;
use App\Core\Models\BusinessCategory;
use App\Appointment\Models\Booking;
use Carbon\Carbon;
use Redis;

class Front extends Base
{
    public function getNextAvailableTimeslots()
    {

    }
}
