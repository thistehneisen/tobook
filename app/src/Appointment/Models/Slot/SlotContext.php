<?php namespace App\Appointment\Models\Slot;

class SlotContext
{
    private $strategy;

    public function __construct(SlotStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function determineClass($employee, $date, $hour, $minute)
    {
        return $this->strategy->determineClass($employee, $date, $hour, $minute);
    }
}
