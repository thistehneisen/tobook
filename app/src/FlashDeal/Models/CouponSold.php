<?php
namespace App\FlashDeal\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CouponSold extends Eloquent
{
    protected $table = 'fd_coupon_solds';
    
    public function coupon()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Coupon', 'coupon_id');
    }
    
    public function consumer()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Consumer', 'consumer_id');
    }
}
