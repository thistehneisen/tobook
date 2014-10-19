<?php namespace App\Cart;

use Carbon\Carbon;
use App\Core\Models\User;
use Illuminate\Support\Collection;

class Cart extends \AppModel
{
    public $fillable = ['status'];

    const STATUS_INIT          = 1; // 01
    const STATUS_COMPLETED     = 2; // 11

    /**
     * Create a new cart item
     *
     * @param array                    $data
     * @param int|App\Core\Models\User $userId The ID of user this consumer belongs to
     *
     * @throws Watson\Validating\ValidationException
     * @throws Illuminate\Database\QueryException
     *
     * @return App\Core\Model\Cart
     */
    public static function make(array $data, $userId)
    {
        $user = ($userId instanceof \App\Core\Models\User)
            ? $userId
            : User::findOrFail($userId);

        $cart = new static();
        $cart->fill($data);
        $cart->user()->associate($user);
        $cart->saveOrFail();

        return $cart;
    }

    /**
     * Check if the cart is empty or not
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->details->isEmpty();
    }

    /**
     * Add a detail item into cart
     *
     * @param CartDetailInterface $item
     *
     * @param array $data
     */
    public function addDetail(CartDetailInterface $item)
    {
        $detail = CartDetail::make($item);
        $this->details()->save($detail);

        return $this;
    }

    /**
     * Add many items into cart
     *
     * @param array|Illuminate\Support\Collection $details
     */
    public function addDetails($details)
    {
        array_walk($details, [$this, 'addDetail']);
        return $this;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function getTotalAttribute()
    {
        $total = 0.0;
        if ($this->details !== null) {
            foreach ($this->details as $detail) {
                $total += $detail->quantity * $detail->price;
            }
        }
        return round($total, 2);
    }

    public function getTotalItemsAttribute()
    {
        if ($this->details !== null) {
            return array_sum($this->details->lists('quantity'));
        }

        return 0;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function details()
    {
        return $this->hasMany('App\Cart\CartDetail');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
