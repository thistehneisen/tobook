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

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getDiscountPercentAttribute()
    {
        $servicePrice = $this->service->price;

        return round(($servicePrice - $this->attributes['discounted_price']) * 100 / $servicePrice, 2);
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
