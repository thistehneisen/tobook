<?php namespace App\Appointment\Models;

use App\Core\Models\Base;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use App\Appointment\Models\Reception\BackendReceptionist;

class FlashDeal extends Base
{
    protected $table = 'as_flash_deals';

    public $fillable = [
        'date',
        'start_at',
        'end_at',
        'discount_percentage',
    ];

    protected $rulesets = [
        'saving' => [
            'discount_percentage' => 'required|numeric',
        ]
    ];

    /**
     * Function to check if we can create a new flash deal with this service in certain date/time.
     * It needs to check each services, since different service may require different resources
     */
    public static function canMakeFlashDeal($user, $employeeId, $serviceId, $bookingDate, $startTime, $length)
    {
        $exception = [];
        try{
            $receptionist = new BackendReceptionist();
            $receptionist->setBookingId(0)
                ->setUUID(null)
                ->setUser($user)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTime)
                ->setServiceId($serviceId)
                ->setModifyTime(0)
                ->setEmployeeId($employeeId)
                ->setServiceTimeId('default')
                ->setBookingServiceId(0)
                ->setIsRequestedEmployee(null);

            $receptionist->validateBooking();

        } catch(\Exception $ex){
            $exception[] = $ex->getMessage();
        }
        return (empty($exception)) ? true : false;
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getDiscountPercentAttribute()
    {
        $servicePrice = $this->service->price;

        return round(($servicePrice - $this->attributes['discounted_price']) * 100 / $servicePrice, 0);
    }

    public function getNormalPriceAttribute()
    {
        return $this->service->price;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    /**
     * Get flash deals of a business
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  App\Core\Models\Business $business
     *
     * @return Illuminate\Support\Collection
     */
    public function scopeOfBusiness($query, $business)
    {
        $id = $business instanceof \App\Core\Models\Business
            ? $business->user_id
            : $business->id;
        return $query->where('user_id', $id);
    }
}
