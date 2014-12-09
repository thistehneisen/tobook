<?php namespace App\Appointment\Models\Reception;

class BackendReceptionist extends Receptionist
{
    public function computeTotalPrice()
    {
        if(empty($this->selectedService)) {
            $this->setSelectedService();
        }

        $this->price = $this->selectedService->price;

        return $this->price;
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
