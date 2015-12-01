<?php namespace App\Appointment\Models\Reception;

use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\ConfirmationReminder;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;
use Exception;
use Carbon\Carbon;

class BackendReceptionist extends Receptionist
{
    public function validateData()
    {
        $this->validateBookingTotal();
        $this->validateBookingTime();
    }

    public function validateBooking()
    {
        $this->validateWithEmployeeFreetime();
        $this->validateWithExistingBooking();
        $this->validateWithResources();
        $this->validateWithRooms();
    }

    /**
     * Using for update booking service times where user delete one of them
     */
    public function updateBookingServicesTime()
    {
        $first = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->first();

        if (!empty($first)) {
            $first->start_at = $this->startTime->toTimeString();
            $first->end_at = $first->startTime->copy()->addMinutes($first->calculcateTotalLength())->toTimeString();
            $first->save();

            $bookingServices = BookingService::where('tmp_uuid', $this->uuid)
                ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->get();

            $previousBookingService = [];
            $lastBookingService = null;
            //Update the sequence of booking service
            //The next start time = the previous end time
            foreach ($bookingServices as $bookingService) {
                if (empty($previousBookingService)) {
                    $previousBookingService[] = $bookingService;
                    continue;
                } else {
                    $lastBookingService = $previousBookingService[count($previousBookingService) - 1];
                }

                if (!empty($lastBookingService)) {
                    $bookingService->start_at = $lastBookingService->endTime->toTimeString();
                    $bookingService->end_at = $lastBookingService->endTime
                        ->copy()->addMinutes($bookingService->calculcateTotalLength())->toTimeString();
                    $bookingService->save();
                }
            }
        }
    }

    public function calculateMultipleBookingServices()
    {
        $startTime = $endTime = $plustime = $totalPrice = $totalLength = null;

        $booking = Booking::find($this->bookingId);

        foreach ($this->bookingServices as $bookingService) {
            $totalLength += $bookingService->calculcateTotalLength(false, false);
            $totalPrice  += $bookingService->calculcateTotalPrice(false);
            $plustime    += $bookingService->getEmployeePlustime();
        }

        //Add new booking extra services if needed
        if (!empty($this->extraServiceIds)) {
            foreach ($this->extraServiceIds as $extraServiceId) {
                $extraService = ExtraService::find($extraServiceId);
                $bookingExtraService = new BookingExtraService();
                $bookingExtraService->fill([
                    'date'     => $this->bookingServices->first()->date,
                    'tmp_uuid' => $this->uuid
                ]);
                $bookingExtraService->extraService()->associate($extraService);
                if (!empty($booking)) {
                    $bookingExtraService->booking()->associate($booking);
                }
                $bookingExtraService->save();
            }
        }

        //Calculate extra services price and time which is associated with current booking
        $this->setExtraServices();
        foreach ($this->extraServices as $bookingExtraService) {
            $totalLength += $bookingExtraService->extraService->length;
            $totalPrice  += $bookingExtraService->extraService->price;
        }

        $totalLength += $this->modifyTime;

        $date             = $this->bookingServices->first()->date;
        $this->startTime  = $startTime = $this->bookingServices->first()->startTime;
        $this->endTime    = $endTime   = $this->bookingServices->first()->startTime->copy()->addMinutes($totalLength);
        $this->date       = $this->bookingServices->first()->startTime->toDateString();
        $this->employeeId = $this->bookingServices->first()->employee_id;

        if ($endTime <= $startTime) {
            throw new \Exception(trans('as.bookings.error.empty_total_time'), 1);
        }

        return array($date, $startTime, $endTime, $plustime, $totalLength, $totalPrice);
    }

    public function isBookable()
    {
        try {
            $this->validateBooking();
        } catch (\Exception $ex){
            return false;
        }
        return true;
    }

    public function upsertBooking()
    {
        $this->setBookingService();
        $this->validateEmptyBookingService();

        $booking = (empty($this->bookingId))
            ? new Booking()
            : Booking::find($this->bookingId);

        $this->employee = $this->bookingService->employee;
        $this->extraServices = (!empty($this->bookingId))
            ? $booking->extraServices
            : array();

        list($date, $startTime, $endTime, $plustime, $totalLength, $totalPrice) = $this->calculateMultipleBookingServices();

        $this->validateWithExistingBooking();

        //Don't change booking source for those bookings from frontend + inhouse
        if (!empty($booking->source) && $booking->source !== $this->getSource()) {
            $this->setSource($booking->source);
        }

        $confirmationReminder = (!empty($booking->reminder->booking_id)) 
            ? $booking->reminder
            : new ConfirmationReminder();

        $this->setReminderEmailAt()->setReminderSmsAt();
        
        $confirmationReminder->fill([
            'is_reminder_sms'          => $this->isReminderSms,
            'reminder_sms_before'      => $this->reminderSmsBefore,
            'reminder_sms_at'          => $this->reminderSmsAt,
            'is_reminder_email'        => $this->isReminderEmail,
            'reminder_email_before'    => $this->reminderEmailBefore,
            'reminder_email_at'        => $this->reminderEmailAt,
            'is_confirmation_email'    => $this->isConfirmationEmail,
            'is_confirmation_sms'      => $this->isConfirmationSms,
            'reminder_email_time_unit' => $this->reminderEmailTimeUnit,
            'reminder_sms_time_unit'   => $this->reminderSmsTimeUnit,
        ]);

        if ((bool)$this->isReminderEmail || (bool)$this->isReminderSms) {
            $now = Carbon::now();
            if ($this->reminderSmsAt->lt($now) || $this->reminderEmailAt->lt($now)) {
                throw new Exception(trans('as.bookings.error.invalid_reminder_time'), 1);
            }
        }

        $booking->fill([
            'date'        => $date,
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $endTime->toTimeString(),
            'total'       => $totalLength,
            'total_price' => $totalPrice,
            'status'      => $this->status,
            'notes'       => $this->notes,
            'uuid'        => $this->uuid,
            'modify_time' => $this->modifyTime,
            'plustime'    => $plustime,
            'ip'          => $this->getClientIp(),
            'source'      => $this->getSource()
        ]);

        $booking->consumer()->associate($this->consumer);
        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->employee);

        if ((int) $this->status === Booking::STATUS_CANCELLED) {
            $booking->delete_reason = 'Cancelled while updating';
            $booking->save();
            $booking->delete();
        } else {
            $booking->save();
            $confirmationReminder->booking()->associate($booking);
            $confirmationReminder->save();
        }

        //Users can check this option before or after save a booking service
        foreach ($this->bookingServices as $bookingService) {
            $bookingService->is_requested_employee = $this->isRequestedEmployee;
            $bookingService->booking()->associate($booking);
            $bookingService->save();
        }

        // Associate extra services with bookings
        $bookingExtraServices = $this->getExtraServices();
        foreach ($bookingExtraServices as $bookingExtraService) {
            $bookingExtraService->booking()->associate($booking);
            $bookingExtraService->save();
        }

        //Don't send sms when update booking
        if (empty($this->bookingId)) {
            //Only can send sms after insert booking service
            $booking->attach(new SmsObserver(true));//true is backend
            $booking->attach(new EmailObserver());
            $booking->notify();
        }

        return $booking;
    }

    public function rollBack()
    {
        if (!empty($this->extraServiceIds)) {
            BookingExtraService::whereIn('extra_service_id',$this->extraServiceIds)->delete();
        }
    }
}
