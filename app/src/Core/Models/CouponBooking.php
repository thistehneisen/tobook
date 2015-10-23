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

class CouponBooking extends Base
{
	protected $table = 'as_coupon_booking';

	public $timestamps = false;

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