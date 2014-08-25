<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Voucher extends Eloquent
{
    protected $table = 'lc_vouchers';
    protected $guarded = ['id'];
}
