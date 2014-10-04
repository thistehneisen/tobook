<?php namespace App\Core\Models;

class CartDetail extends Base
{
     public $fillable = ['item', 'variant', 'quantity', 'price'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Core\Models\Cart');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
