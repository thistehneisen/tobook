<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Offer extends Eloquent
{
    protected $table = 'lc_offers';
    protected $guarded = ['id'];
}
