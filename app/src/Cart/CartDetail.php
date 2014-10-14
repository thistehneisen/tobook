<?php namespace App\Cart;

use App\Appointment\Models\BookingService;

class CartDetail extends \AppModel
{
    public $fillable = ['item', 'variant', 'quantity', 'price'];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getQuantityAttribute()
    {
        return (int) $this->attributes['quantity'];
    }

    public function getPriceAttribute()
    {
        return (double) $this->attributes['price'];
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Cart\Cart');
    }
}
