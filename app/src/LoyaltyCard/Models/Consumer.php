<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Consumer extends Eloquent
{
    protected $table = 'lc_consumer';
    protected $guarded = ['id'];

    /**
     * Define the parent relationship of this consumer
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Core\Models\Consumer');
    }
}
