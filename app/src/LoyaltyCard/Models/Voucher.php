<?php namespace App\LoyaltyCard\Models;

use \App\Core\Models\Base;

class Voucher extends Base
{
    protected $table = 'lc_vouchers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public $fillable = ['name', 'required', 'value', 'type', 'is_active'];
    protected $rulesets = [
        'saving' => [
        'name'          => 'required',
        'required'      => 'required|numeric',
        'value'         => 'required|numeric',
        ]
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
