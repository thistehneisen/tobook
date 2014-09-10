<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;

class Frontend implements Strategy
{
    /**
     * These variables to use as a dictionary to easy to get back
     *  and limit access to db in for loop
     */
    private $bookedSlot     = [];
    private $bookingList    = [];
    private $freetimeSlot   = [];
    private $freetimesCache = [];

    public function determineClass($employee, $date, $hour, $minute){
        //TODO implement the body for front end
        return;
    }
}
