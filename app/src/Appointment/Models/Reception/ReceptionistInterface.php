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

    /**
     * Calculate the total length of the booking based on given data
     *
     * Total length =
     *      (service length | service time length) +
     *          [employee plustime] + [modify time] + [extra service(s) time]
     */
    public function computeLength();

    /**
     * Validate the general correctness, meaningfulness of given data
     */
    public function validateData();

    /**
     * Validate the ability to make the booking by checking these conditions:
     *  - Is it overlapping with existing booking?
     *  - Is it overlapping with employee free time?
     *  - Do we have enough resources (room, equipments etc) for the booking?
     */
    public function validateBooking();

}
