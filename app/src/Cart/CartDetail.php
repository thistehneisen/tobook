<?php namespace App\Cart;

use App;
use Settings;

class CartDetail extends \AppModel
{
    public $fillable = ['model_type', 'model_id', 'quantity', 'price'];

    protected $model;
    protected $name;

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
        $instance->model      = $item->getCartDetailOriginal();
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

    public function getDepositAttribute()
    {
        $depositRate  = (double) Settings::get('deposit_rate');
        return $this->price * $depositRate;
    }

    public function getModelAttribute()
    {
        if ($this->model === null) {
            $model = App::make($this->model_type)->find($this->model_id);

            if ($model !== null) {
                $this->model = $model->getCartDetailOriginal();
            } else {
                $this->delete();
            }
        }
        return $this->model;
    }

    public function setModelAttribute($value)
    {
        $this->model = $value;
    }

    public function getNameAttribute()
    {
        if ($this->name === null) {
            $model = $this->getModelAttribute();
            if (!empty($model)) {
                $this->name = $model->instance->getCartDetailName();
            }
        }
        return $this->name;
    }

    public function setNameAttribute($value)
    {
        $this->name = $value;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Cart\Cart');
    }
}
