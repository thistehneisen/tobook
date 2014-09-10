<?php namespace App\Appointment\Models\Slot;

class Context
{
    private $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function determineClass($employee, $date, $hour, $minute)
    {
        return $this->strategy->determineClass($employee, $date, $hour, $minute);
    }
}
