<?php namespace App\Appointment\Models\Reception;

interface ReceptionistInterface
{
    public function setBookingId($bookingId);
    public function getBookingId();

    public function setBookingDate($date);
    public function getBookingDate();

    public function setStartTime($startTime);
    public function getStartTime();

    public function setUUID($uuid);
    public function getUUID();

    public function setServiceId($serviceId);
    public function getServiceId();

    public function setServiceTimeId($serviceTimeId);
    public function getServiceTimeId();

    public function setIsRequestedEmployee($isRequestedEmployee);
    public function getIsRequestedEmployee();

    public function computeLength();

    public function validateData();
    public function validateBooking();

}
