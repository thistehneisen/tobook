<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;

class Service extends Base
{
    protected $table = 'fd_services';
    public $fillable = [
        'name',
        'price',
        'quantity',
        'description',
    ];
    protected $rulesets = [
        'saving' => [
            'business_category_id' => 'required',
            'name'                 => 'required',
            'price'                => 'required|numeric',
            'quantity'             => 'required|numeric',
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
}
