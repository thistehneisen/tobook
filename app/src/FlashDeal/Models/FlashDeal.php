<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class FlashDeal extends Base
{
    protected $table = 'fd_flash_deals';

    public $fillable = [
        'discounted_price',
        'quantity',
    ];

    protected $rulesets = [
        'saving' => [
            'service_id'       => 'required',
            'discounted_price' => 'required|numeric',
            'quantity'         => 'required|numeric',
        ]
    ];

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = true;

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
        return $this->belongsTo('App\FlashDeal\Models\Service');
    }

    public function dates()
    {
        return $this->hasMany('App\FlashDeal\Models\FlashDealDate');
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

    //--------------------------------------------------------------------------
    // SEARCH
    //--------------------------------------------------------------------------

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocument()
    {
        $data = [
            'discounted_price' => $this->discounted_price,
            'quantity'         => $this->quantity,
        ];

        if ($this->service !== null) {
            $data['service'] = $this->service->name_with_price;
        }

        return $data;
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchMapping()
    {
        return [
            'discounted_price' => ['type' => 'double'],
            'quantity'         => ['type' => 'integer'],
            'service'          => ['type' => 'string'],
        ];
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    /**
     * Get active deals to display in front page
     *
     * @return Illuminate\Support\Collection
     */
    public static function getActiveDeals()
    {
        return static::whereHas('dates', function($query) {
            return $query->active()->orderBy('expire');
        })
        ->with(['user.business', 'service', 'service.businessCategory'])
        ->get();
    }

    /**
     * Get the list of categories that have active offers
     *
     * @param Illuminate\Support\Collection $deals Collection of deals
     *
     * @return Illuminate\Support\Collection
     */
    public static function getDealCategories($deals)
    {
        $data = [];
        foreach ($deals as $deal) {
            $category = $deal->service->businessCategory;
            if (!isset($data[$category->id])) {
                $data[$category->id] = $category;
            }

            $data[$category->id]->totalDeals = isset($data[$category->id]->totalDeals)
                ? $data[$category->id]->totalDeals + 1
                : 1;
        }

        return new Collection($data);
    }
}
