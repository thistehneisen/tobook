<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Voucher extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = 'lc_vouchers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
}
