<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;

class FlashDealDate extends Base
{
    protected $table = 'fd_flash_deal_dates';
    public $fillable = [
        'date',
        'time',
    ];
    protected $rulesets = [
        'saving' => [
            'date' => 'required',
            'time' => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function flashdeals()
    {
        return $this->belongsTo('App\FlashDeal\Models\FlashDeal');
    }
}
