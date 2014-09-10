<?php namespace App\Appointment\Models\Slot;

interface SlotStrategy {
    public function determineClass($employee, $date, $hour, $minute);
}
