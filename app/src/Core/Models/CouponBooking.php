<?php namespace App\Core\Models;

use App;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Discount\DiscountBusiness;
use App\Core\Models\Relations\BusinessBusinessCategory;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Collection;
use Input;
use Settings;
use Str;
use Util;
use Log;

class CouponBooking extends \Eloquent
{
    protected $primaryKey = 'booking_id';
	protected $table = 'as_coupon_booking';

	public $timestamps = false;


    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function releaseCoupon($cutoff)
    {
        Log::info('Started to unlock coupon codes');

        $couponBookings = self::where('as_bookings.status','!=', Booking::STATUS_PAID)
            ->join('as_bookings', 'as_bookings.id', '=', 'as_coupon_booking.booking_id')
            ->where('as_bookings.status', '!=', Booking::STATUS_CONFIRM)
            ->where('as_bookings.created_at', '<=', $cutoff)
            ->whereNull('as_bookings.deleted_at')
            ->orderBy('as_bookings.id', 'desc')
            ->get();

        Log::info('Found ' . $couponBookings->count() . ' commissions');

        // Go through all cart details and release them
        foreach ($couponBookings as $coupon) {
            $coupon->forceDelete();
        }

        Log::info('Release coupons are done');
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function coupon()
    {
        return $this->belongsTo('App\Core\Models\Coupon');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }
}