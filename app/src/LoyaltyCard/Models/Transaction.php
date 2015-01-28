<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Transaction extends Eloquent
{
    protected $table = 'lc_transactions';

    public $fillable = ['consumer_id'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
