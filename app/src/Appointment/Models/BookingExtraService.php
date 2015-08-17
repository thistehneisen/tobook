<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use Watson\Validating\ValidationException;

class BookingExtraService extends \App\Core\Models\Base
{
    protected $table = 'as_booking_extra_services';

    public $fillable = [
        'date',
        'tmp_uuid',
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function extraService()
    {
        return $this->belongsTo('App\Appointment\Models\ExtraService');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    //--------------------------------------------------------------------------
    // BUSINESS LOGIC
    //--------------------------------------------------------------------------

    /**
     * Add a new extra service to an existing booking service.
     * <code>App\Appointment\Models\Booking::saveBooking</code> needs to be called with the same uuid
     * to associate the new extra service with a booking record.
     *
     * @param string         $uuid
     * @param Employee       $employee
     * @param BookingService $bookingService
     * @param ExtraService   $extraService
     *
     * @return BookingExtraService
     *
     * @throws ValidationException
     */
    public static function addExtraService($uuid, Employee $employee, BookingService $bookingService, ExtraService $extraService)
    {
        $extraServiceStartTime = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $bookingService->date, $bookingService->end_at));

        // delay start time if existing extra services are found
        foreach (BookingExtraService::where('tmp_uuid', '=', $uuid)->get() as $existingBookingExtraService) {
            // TODO check for extra services of this $bookingService only (support multiple services per booking)
            $extraServiceStartTime->addMinutes($existingBookingExtraService->extraService->length);
        }

        $extraServiceEndTime = with(clone $extraServiceStartTime)->addMinutes($extraService->length);

        if (!Booking::isBookable($employee->id, $extraServiceStartTime->toDateString(), $extraServiceStartTime, $extraServiceEndTime, $uuid)) {
            throw new ValidationException(trans('as.bookings.error.add_overlapped_booking'));
        }

        // TODO: check for overlapping with other booking services of the same booking

        $bookingExtraService = new BookingExtraService();
        $bookingExtraService->fill([
            'date' => $extraServiceStartTime->toDateString(),
            'tmp_uuid' => $uuid,
        ]);
        $bookingExtraService->extraService()->associate($extraService);
        $bookingExtraService->saveOrFail();

        return $bookingExtraService;
    }
}
