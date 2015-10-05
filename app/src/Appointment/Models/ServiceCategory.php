<?php namespace App\Appointment\Models;
use Config, Input;
use App\Core\Models\Multilanguage;
use App\Appointment\Models\Discount;

class ServiceCategory extends \App\Core\Models\Base
{
    protected $table = 'as_service_categories';

    protected $dates = ['deleted_at'];

    public $fillable = ['name', 'description', 'is_show_front'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    /**
     * @see \App\Core\Models\Base
     */
    public $multilingualAtrributes = ['name', 'description'];

    /**
     * Get current context for retreive correct translation in multilanguage table
     *
     * @return string
     */
    public static function getContext()
    {
        return "as_service_categories_";
    }

    public function isDeletable()
    {
        return ($this->services->isEmpty()) ? true : false;
    }

    public function saveMultilanguage($names, $descriptions)
    {
        Multilanguage::saveValues($this->id, static::getContext(), 'name', $names);
        Multilanguage::saveValues($this->id, static::getContext(), 'description', $descriptions);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsShowFrontAttribute($value)
    {
        $this->attributes['is_show_front'] = (bool) $value;
    }

    public function getIsShowFrontAttribute()
    {
        return (isset($this->attributes['is_show_front'])) ? (bool) $this->attributes['is_show_front'] : true;
    }

    public function getPriceRangeAttribute()
    {
       
        $priceRange = '';
        $result     = '';
        $prices     = [];

        $result['service'] = [];
        
        foreach($this->services as $service) {
            $prices[] = $service->price;
            $serviceRange[] = $service->price;
            foreach ($service->serviceTimes as $serviceTime) {
                $prices[] = $serviceTime->price;
                $serviceRange[] = $serviceTime->price;
            }
            $result['service'][$service->id] = $this->getPriceRange($serviceRange);
        }

      
        $result['category'] = $this->getPriceRange($prices);
        return $result;
    }

    private function getPriceRange($prices)
    {
        $mformatted = '%d&euro; &ndash; %d&euro;';
        $oformatted = '%d&euro;';

        if (count($prices) < 1) {
            $prices[] = 0;
        }

        $min = min($prices);
        $max = max($prices);

        if (count($prices) < 2) {
           $priceRange = sprintf($oformatted, $max);
        } else {
           $priceRange = ($min !== $max)
                ? sprintf($mformatted, $min, $max)
                : sprintf($oformatted, $max);
        }

        return $priceRange;
    }

    public function getHasDiscountAttribute()
    {
        $hashDiscount = false;

        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)
            ->where('discount', '>', 0)->first();

        $discountLastMinute = DiscountLastMinute::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)
            ->where('discount', '>', 0)->first();

        $hashDiscount = (empty($discount) && empty($discountLastMinute)) ? false : true;

        if (!empty($discount)) {
            foreach($this->services as $service) {
                if($service->is_discount_included === true) {
                    $hashDiscount = true;
                    break;
                }
            }
        }

        return $hashDiscount;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service', 'category_id');
    }
}
