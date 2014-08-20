<?php namespace App\LoyaltyCard\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Consumer extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'lc_consumer';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $guarded = ['id'];
}
