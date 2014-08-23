<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Consumer as CoreConsumer;

class Consumer extends CoreConsumer
{
    protected $table = 'lc_consumers';
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
