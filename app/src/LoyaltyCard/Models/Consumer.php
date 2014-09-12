<?php namespace App\LoyaltyCard\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Consumer extends \App\Core\Models\Base
{
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
