<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Consumer as CoreConsumer;

class Consumer extends CoreConsumer
{
    protected $table = 'lc_consumers';
    protected $guarded = ['id'];
}
