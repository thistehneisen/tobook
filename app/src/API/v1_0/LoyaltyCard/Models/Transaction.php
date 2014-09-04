<?php namespace App\API\v1_0\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Transaction extends Eloquent
{
    protected $table = 'lc_transactions';
    protected $guarded = ['id'];
}
