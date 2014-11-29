<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;

class Service extends Base
{
    protected $table = 'fd_services';
    public $fillable = [
        'name',
        'price',
        'description',
    ];
    protected $rulesets = [
        'saving' => [
            'business_category_id' => 'required',
            'name'                 => 'required',
            'price'                => 'required|numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function businessCategory()
    {
        return $this->belongsTo('App\Core\Models\BusinessCategory');
    }

    public function coupons()
    {
        return $this->hasMany('App\FlashDeal\Models\Coupon');
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getNameWithPriceAttribute()
    {
        $price = $this->attributes['price'];

        return $this->attributes['name']." (&euro;$price)";
    }

    //--------------------------------------------------------------------------
    // SEARCH
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getSearchDocument()
    {
        return [
            'name'     => $this->name,
            'price'    => $this->price,
            'category' => $this->businessCategory->name ?: ''
        ];
    }
}
