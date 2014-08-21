<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Offer extends Eloquent
{
    protected $table = 'lc_offers';
    protected $guarded = ['id'];

    /**
     * Define the parent relationship of this offer
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer()
    {
        return $this->belongsTo('App\Core\Models\Offer');
    }
}
