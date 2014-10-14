<?php namespace App\Cart;

class CartDetail extends \AppModel
{
    public $fillable = ['model_type', 'model_id', 'quantity', 'price'];

    /**
     * Extra data from provide object and create a cart detail instance
     *
     * @param CartDetailInterface $item
     *
     * @return App\Cart\Detail
     */
    public static function make(CartDetailInterface $item)
    {
        $instance = new static;
        $instance->quantity   = $item->getCartDetailQuantity();
        $instance->price      = $item->getCartDetailPrice();
        $instance->name       = $item->getCartDetailName();
        $instance->model_id   = $item->id;
        $instance->model_type = get_class($item);

        return $instance;
    }

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
