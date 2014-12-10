<?php namespace App\Appointment\Models\Reception;

class FrontendReceptionist extends Receptionist
{
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
