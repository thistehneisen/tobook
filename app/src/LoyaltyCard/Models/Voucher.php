<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Voucher extends Eloquent
{
    protected $table = 'lc_vouchers';
    protected $guarded = ['id'];

    /**
     * Define the parent relationship of this voucher
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo('App\Core\Models\Voucher');
    }
}
