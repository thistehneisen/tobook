<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Consumer extends Eloquent
{
    protected $table = 'lc_consumer';
    protected $guarded = ['id'];
}
