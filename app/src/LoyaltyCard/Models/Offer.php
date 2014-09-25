<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Base;

class Offer extends Base
{
    protected $table = 'lc_offers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public $fillable = ['name', 'required', 'is_active', 'is_auto_add'];
    protected $rulesets = [
        'saving' => [
            'name'          => 'required',
            'required'      => 'required|numeric',
        ]
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
