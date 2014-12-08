<?php namespace App\Appointment\Models\Reception;

class BackendReceptionist extends Receptionist
{

    public function computeLength()
    {

    }

    public function validateData()
    {
        $this->validateBookingTotal();
        $this->validateBookingEndTime();
    }

    public function validateBooking()
    {
        $this->validateWithEmployeeFreetime();
        $this->validateWithExistingBooking();
        $this->validateWithResources();
    }
}
