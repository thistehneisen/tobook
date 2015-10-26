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

class Campaign extends Base
{
	protected $table = 'as_coupon_campaigns';
	
	public $fillable = [
        'name',
        'discount_type',
        'discount',
        'is_reusable',
        'reusable_code',
        'amount',
        'begin_at',
        'expire_at',
        'is_active'
    ];

    public $rulesets = [
        'saving' => [
            'name' => 'required',
            'discount' => 'required',
            'amount' => 'required',
            'discount_type' => 'required',
            'is_reusable' => 'required',
            'expire_at' => 'required',
        ]
    ];


    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    public function generateCodes($amount = 0)
    {  
        if ($amount < 1) return [];
        
        $codes = [];

        while($amount) {
            $code = Str::quickRandom(10);
            if( ! in_array($code, $codes)) {
                $codes[] = $code;
                $amount--;
            }
        }

        return $codes;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function coupons()
    {
        return $this->hasMany('App\Core\Models\Coupon', 'campaign_id');
    }
}