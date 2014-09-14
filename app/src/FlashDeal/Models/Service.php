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
            'name'     => 'required',
            'price'    => 'required|numeric',
            'quantity' => 'required|numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
