<?php namespace App\Appointment\Listeners;

use Log;
use Event;
use Cart;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;
use App\Core\Models\BusinessCommission;

class PaymentSuccessListener
{
    /**
     * Update status of booking associated with this transaction
     *
     * @param App\Payment\Models\Transaction $transaction
     *
     * @return void
     */
    public function handle($transaction)
    {
        $cart = $transaction->cart;
        if ($cart === null) {
            Log::debug('Cannot complete AS booking because the cart is empty', ['transaction' => $transaction]);
            // Nothing to do with this
            return;
        }

        // Complete the cart
        $cart->complete();

        // Find all booking service IDs
        $bookingServiceIds = $cart->details->lists('model_id');
        $bookingServices = BookingService::whereIn('id', $bookingServiceIds)
            ->with('booking')
            ->get();

        // Update related bookings
        foreach ($bookingServices as $item) {
            if ($item->booking !== null) {
                $paymentType = '';
                if (!$cart->isDepositPayment) {
                    $item->booking->status = Booking::STATUS_PAID;
                    $paymentType           = BusinessCommission::PAYMENT_FULL;
                } else {
                    $item->booking->status  = Booking::STATUS_CONFIRM;
                    $item->booking->deposit = $cart->depositTotal;
                    $paymentType            = BusinessCommission::PAYMENT_DEPOSIT;
                }

                $item->booking->save();

                 //Send notification email and SMSs
                try {
                    $item->booking->attach(new EmailObserver());
                    $item->booking->attach(new SmsObserver());
                    $item->booking->notify();

                    Log::info('Update business commission');
                    BusinessCommission::updateCommission($item->booking, $paymentType);

                    //Send calendar invitation to employee
                    Event::fire('employee.calendar.invitation.send', [$item->booking]);
                } catch (\Exception $ex) {
                    //Log error info to laravel.log
                    Log::warning('Could not send sms or email:' . $ex->getMessage());
                }
            }
        }

        // Done
    }

}
