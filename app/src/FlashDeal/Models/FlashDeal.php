<?php namespace App\FlashDeal\Models;

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
}
