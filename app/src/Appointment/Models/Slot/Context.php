<?php namespace App\Appointment\Models\Slot;

class Context
{
    private $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function determineClass($date, $hour, $minute, $employee, $service = null)
    {
        return $this->strategy->determineClass($date, $hour, $minute, $employee, $service);
    }
}
