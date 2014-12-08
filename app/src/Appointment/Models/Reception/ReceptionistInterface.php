<?php namespace App\Appointment\Models\Reception;

interface ReceptionistInterface
{
    public function setBookingId(int $bookingId);
    public function getBookingId();

    public function setBookingDate(string $date);
    public function getBookingDate();

    public function setStartTime(string $startTime);
    public function getStartTime();

    public function setUUID(string $uuid);
    public function getUUID();

    public function setServiceId(int $serviceId);
    public function getServiceId();

    public function setServiceTimeId(int $serviceTimeId);
    public function getServiceTimeId();

    public function setIsRequestedEmployee(bool $isRequestedEmployee);
    public function getIsRequestedEmployee();

    public function computeLength();

    public function validateData();
    public function validateBooking();

}
