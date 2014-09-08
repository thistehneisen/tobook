<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Session, Request;
use Illuminate\Support\Collection;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use Carbon\Carbon;

class FrontBookings extends Bookings
{
    //Use this controller to avoid authentication in Bookings
}
