<?php namespace App\FlashDeal\Models;

use Carbon\Carbon, DB;
use App\Core\Models\Base;

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
        return $query->where('user_id', $business->id);
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
}
