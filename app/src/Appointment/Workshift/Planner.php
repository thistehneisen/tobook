<?php namespace App\Appointment\Workshift;

use Session, Config, Carbon\Carbon, Log, Event, Settings;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Core\Models\User;

class Planner {
    private $strategy;

    public function __construct($strategy = 'normal') {

    }

    public function init() {

    }

    public function getWeekSummary() {

    }

    public function getMonthSummary() {

    }
}
