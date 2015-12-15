<?php namespace App\Appointment\Models\Reception;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Core\Models\Coupon;
use App\Core\Models\Campaign;
use Settings;
use Exception;

class FrontendReceptionist extends Receptionist
{

    /**
     * This is real start time of an booking
     * because in frontend we don't show the `before` time
     */
    public function getStartTime()
    {
        return $this->startTime->copy()->subMinutes($this->getSelectedService()->before);
    }

    /**
     * This is real end time of an booking
     *  because in frontend we don't show the `after` time
     */
    public function getEndTime()
    {
        $this->endTime = $this->getStartTime()->copy()->addMinutes($this->getLength());

        return $this->endTime;
    }

    public function validateData()
    {
        $this->validateBookingTotal();
        $this->validateBookingTime();
    }

    public function validateBooking()
    {
        $this->validateWithEmployeeFreetime();
        // $this->isValidWithCustomTime();
        $this->validateWithExistingBooking();
        $this->validateWithResources();
        $this->validateWithRooms();
        $this->validateMinMaxDistance();
    }

    /**
     * Validate booking date with min and max distance
     * @see https://github.com/varaa/varaa/issues/192
     */
    public function validateMinMaxDistance()
    {
        $today = Carbon::today();
        $minDistance = (int) $this->user->asOptions['min_distance'];
        $maxDistance = (int) $this->user->asOptions['max_distance'];

        $start  = $today->copy()->addHours($minDistance);
        $final  = ($maxDistance)
            ? $today->copy()->addDays($maxDistance)
            : $today->copy()->addDays(3650);

        if ($this->getStartTime()->lt($start)) {
            throw new Exception(trans('as.bookings.error.before_min_distance'), 1);
        }

        if ($this->getStartTime()->gt($final)) {
            throw new Exception(trans('as.bookings.error.after_max_distance'), 1);
        }
    }

    public function upsertBooking()
    {
        $this->setBookingService();
        $this->validateEmptyBookingService();

        $booking = new Booking();

        $status = ($this->getSource() === 'inhouse' && !((int) $this->layout === 3))
            ? Booking::STATUS_PENDING
            : Booking::STATUS_CONFIRM;

        $totalPrice = ($this->source === 'cp')
            ? $this->bookingService->service->getDiscountPrice(
                    $this->bookingService->date,
                    $this->bookingService->startTime
                )
            : $this->bookingService->calculcateTotalPrice();

        $this->validateWithExistingBooking();

        $totalPrice = Coupon::computePrice($this->coupon, $totalPrice);

        $booking->fill([
            'date'        => $this->bookingService->date,
            'start_at'    => $this->bookingService->startTime->toTimeString(),
            'end_at'      => $this->bookingService->endTime->toTimeString(),
            'total'       => $this->bookingService->calculcateTotalLength(),
            'total_price' => $totalPrice,
            'uuid'        => $this->bookingService->tmp_uuid,
            'modify_time' => $this->bookingService->modify_time,
            'plustime'    => $this->bookingService->getEmployeePlustime(),
            'notes'       => $this->notes,
            'status'      => $status,
            'ip'          => $this->getClientIp(),
            'source'      => $this->getSource()
        ]);

        if ($this->getSource() !== 'backend' && $this->consumer !== null) {
            $booking->consumer()->associate($this->consumer);
        }

        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->bookingService->employee);
        $booking->save();

        $this->bookingService->booking()->associate($booking);
        $this->bookingService->is_requested_employee = $this->getIsRequestedEmployee();
        $this->bookingService->save();

        $this->setExtraServices();

        foreach ($this->extraServices as $extraService) {
            $extraService->booking()->associate($booking);
            $extraService->save();
        }

        if ( ! empty($this->coupon)) {
            $this->saveCoupon($booking);
        }

        return $booking;
    }
}
