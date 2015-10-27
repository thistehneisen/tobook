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

class Coupon extends Base
{
	protected $table = 'as_coupons';
    
	public $fillable = [
        'code',
        'is_used',
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getIsUsedAttribute()
    {
        return ((boolean) $this->attributes['is_used']) ? 'used' : 'not_used';
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function campaign()
    {
        return $this->belongsTo('App\Core\Models\Campaign');
    }
}