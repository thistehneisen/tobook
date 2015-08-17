<?php namespace App\Appointment\Models\Slot;

interface Strategy
{
    public function determineClass($employee, $date, $hour, $minute);
}
