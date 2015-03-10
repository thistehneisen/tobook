<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;
use Config;
use Illuminate\Support\Collection;
use Settings;

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

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = true;

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

        return sprintf('%s (%s%s)', $this->attributes['name'], $price, Settings::get('currency'));
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

    /**
     * @{@inheritdoc}
     */
    public function getSearchMapping()
    {
        return [
            'name'     => ['type' => 'string'],
            'price'    => ['type' => 'double'],
            'category' => ['type' => 'string'],
        ];
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
}
