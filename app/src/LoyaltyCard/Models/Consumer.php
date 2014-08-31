<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Consumer as CoreConsumer;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Consumer extends CoreConsumer
{
    use SoftDeletingTrait;

    protected $table = 'lc_consumers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    /**
     * Define the parent relationship of this consumer
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
