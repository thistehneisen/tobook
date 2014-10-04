<?php namespace App\Core\Models;

class Cart extends Base
{
    public $fillable = ['status'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
