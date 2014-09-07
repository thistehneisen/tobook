<?php
namespace App\FlashDeal\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Coupon extends Eloquent
{
    protected $table = 'fd_coupons';
    
    public function service()
    {
        return $this->belongsTo('\App\FlashDeal\Models\Service', 'service_id');
    }
    
    public function solds()
    {
        return $this->hasMany('\App\FlashDeal\Models\CouponSold', 'coupon_id');
    }  
}
