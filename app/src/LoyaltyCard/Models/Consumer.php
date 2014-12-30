<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Base;

class Consumer extends Base
{
    protected $table = 'lc_consumers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public $fillable = ['total_points', 'total_stamps'];
    protected $rulesets = [
        'saving' => []
    ];

    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
