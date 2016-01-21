<?php namespace App\Appointment\Models;

use Config;
use Settings;
use App;
use App\Core\Models\Multilanguage;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
use Carbon\Carbon;
use Redis;
use Log;

class Service extends \App\Core\Models\Base
{
    protected $table = 'as_services';

    public $fillable = ['name', 'price','length','before','during', 'after', 'description', 'is_active', 'is_discount_included'];

    protected $rulesets = [
        'saving' => [
            'name'        => 'required',
            'category_id' => 'required'
        ]
    ];

    use \App\Appointment\Models\Discount\DiscountPrice;

    /**
     * @see \App\Core\Models\Base
     */
    public $multilingualAtrributes = ['name', 'description'];

    public function saveMultilanguage($names, $descriptions)
    {
        Multilanguage::saveValues($this->id, static::getContext(), 'name', $names);
        Multilanguage::saveValues($this->id, static::getContext(), 'description', $descriptions);
    }

    public function isDeletable()
    {
        $bookings = Booking::join('as_booking_services', 'as_booking_services.booking_id', '=', 'as_bookings.id')
            ->where('as_bookings.status', '!=', Booking::STATUS_CANCELLED)
            ->where('as_booking_services.service_id', $this->id)->get();

        return ($bookings->isEmpty()) ? true : false;
    }

    public function requireRoom()
    {
        return ($this->rooms()->count()) ? true : false;
    }

    /**
     * Get current context for retreive correct translation in multilanguage table
     *
     * @return string
     */
    public static function getContext()
    {
        return "as_services_";
    }

    /**
     * Generate service times for displaying on booking form
     *
     * @return array
     */
    public function getServiceTimesData($employeeId = null)
    {
        $serviceTimes = $this->serviceTimes;
        $data = [];
        $employee = (!empty($employeeId))
            ? $this->employees()->where('employee_id', $employeeId)->first()
            : null;

        $plustime = (!empty($employee))
            ? $employee->getPlustime($this->id)
            : 0;

        $data[-1] = [
            'id'          => -1,
            'name'        => sprintf('-- %s --', trans('common.select')),
            'length'      => 0,
            'description' => ''
        ];

        $serviceLength = $this->length + $plustime;

        $data['default'] = [
            'id'          => 'default',
            'name'        => $serviceLength,
            'length'      => $serviceLength,
            'price'       => $this->price,
            'description' => $this->description
        ];
        foreach ($serviceTimes as $serviceTime) {
            $serviceTimeLength = $serviceTime->length + $plustime;
            $data[$serviceTime->id] = [
                'id'            => $serviceTime->id,
                'name'          => $serviceTimeLength,
                'length'        => $serviceTimeLength,
                'price'         => $serviceTime->price,
                'description'   => $serviceTime->description
            ];
        }

        return array_values($data);
    }

    public function getTreamentsList()
    {
        if (!empty($this->master_category_id)) {
            return TreatmentType::where('master_category_id', $this->master_category_id)->get()->lists('name', 'id');
        }

        return [];
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getIsActiveAttribute()
    {
        return (bool) $this->attributes['is_active'];
    }

    public function getFormattedPriceAttribute()
    {
        return show_money($this->attributes['price']);
    }

    public function setLength()
    {
        $this->length = $this->after + $this->during + $this->before;
    }

    public function getHasDiscountAttribute()
    {
        $hashDiscount = false;

        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)->first();
        $discountLastMinute = DiscountLastMinute::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)->first();

        $hashDiscount = (empty($discount) && empty($discountLastMinute)) ? false : true;
        $hashDiscount = ($hashDiscount && $service->is_discount_included);

        return $hashDiscount;
    }

    public function getHasEmployeeAttribute()
    {
        return boolval((int)$this->employees()->count());
    }

    /**
     * Get price range of current service inlucding all service times
     * 
     * @author hung@varaa.com
     * @return string
     */ 
    public function getPriceRangeAttribute()
    {   
        // m for multiple
        $mformatted = '%d&euro; &ndash; %d&euro;';
        // o for one
        $oformatted = '%d&euro;';

        $prices[] = $this->price;
        foreach ($this->serviceTimes as $serviceTime) {
            $prices[] = $serviceTime->price;
        }

        if (count($prices) < 1) {
            $prices[] = 0;
        }

        $min = min($prices);
        $max = max($prices);

        if (count($prices) < 2) {
           $result = sprintf($oformatted, $max);
        } else {
           $result = ($min !== $max)
                ? sprintf($mformatted, $min, $max)
                : sprintf($oformatted, $max);
        }

        return $result;
    }

    public static function getMostPopularServices($user_id, $type, $id)
    {
        $redis =  Redis::connection();
        $key = ($type === 'category')
            ? 'ps_%s_mc_%s' 
            : 'ps_%s_tc_%s';

        $key = sprintf($key, $user_id, $id);
        $ids = json_decode($redis->get($key));
        $services = Service::whereIn('id', $ids)->get();

        return $services;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Appointment\Models\ServiceCategory');
    }

    public function masterCategory()
    {
        return $this->belongsTo('App\Appointment\Models\MasterCategory');
    }

    public function treatmentType()
    {
        return $this->belongsTo('App\Appointment\Models\TreatmentType');
    }

    public function employees()
    {
        return $this->belongsToMany('App\Appointment\Models\Employee', 'as_employee_service')->withPivot('plustime');
    }

    public function serviceTimes()
    {
        return $this->hasMany('App\Appointment\Models\ServiceTime');
    }

    public function resources()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\Resource',
            'as_resource_service'
        );
    }

    public function rooms()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\Room',
            'as_room_service'
        );
    }

    /**
     * @see http://laravel.com/docs/eloquent#many-to-many
     */
    public function extraServices()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\ExtraService',
            'as_extra_service_service',
            'service_id',
            'extra_service_id'
        );
    }
}
