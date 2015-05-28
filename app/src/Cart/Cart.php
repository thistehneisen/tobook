<?php namespace App\Cart;

use Session, Config, Carbon\Carbon, Log, Event, Settings;
use App\Core\Models\User;
use Illuminate\Support\Collection;

class Cart extends \AppModel
{
    public $fillable = ['status'];

    const STATUS_INIT      = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3; // payment is cancelled
    const STATUS_ABANDONED = 4; // cart was not touched in 15 minutes
    const SESSION_NAME     = 'current.cart';

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

        // Update session
        $cart->updateSession();

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

        $this->updateSession();

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

    /**
     * Return the current cart in session
     *
     * @return App\Cart\Cart;
     */
    public static function current()
    {
        if (Session::has(static::SESSION_NAME)) {
            $cartId = Session::get(static::SESSION_NAME);

            // If the current cart is completed, we don't return in
            return static::where('status', '!=', static::STATUS_COMPLETED)
                ->find($cartId);
        }
    }

    /**
     * Dump the current cart into session
     *
     * @return App\Cart\Cart
     */
    public function updateSession()
    {
        Session::put(static::SESSION_NAME, $this->id);

        return $this;
    }

    /**
     * Remove a detail from this cart
     *
     * @param int $id
     *
     * @return App\Cart\Cart
     */
    public function remove($id)
    {
        $this->details()->where('id', $id)->delete();

        return $this;
    }

    /**
     * Release items put in the cart so that they're bookable again
     *
     * @param  Carbon $cutoff
     * @return void
     */
    public static function scheduledUnlock(Carbon $cutoff)
    {
        Log::info('Started to unlock cart items');

        // Get all carts whose status is `init` in the last X minutes
        // (X is the maximum time to hold an item, configurable)
        $carts = static::where('status', '=', static::STATUS_INIT)
            ->where('created_at', '<=', $cutoff)
            ->orderBy('id', 'desc')
            ->get();

        Log::info('Found '.$carts->count().' carts');

        // Go through all cart details and release them
        foreach ($carts as $cart) {
            $cart->unlock();
        }

        Log::info('Unlocking cart items done');
    }

    /**
     * Go through all cart detail objects and unlock
     *
     * @return void
     */
    public function unlock()
    {
        if (!$this->details->isEmpty()) {
            foreach ($this->details as $detail) {
                if (!empty($detail->model->instance)) {
                    $detail->model->instance->unlockCartDetail($detail);
                }
            }
        }

        // Update cart status to UNLOCKED
        $this->status = static::STATUS_ABANDONED;
        $this->save();
    }

    /**
     * Set status of a cart to be completed
     *
     * @return App\Cart\Cart
     */
    public function complete()
    {
        $this->status = static::STATUS_COMPLETED;
        $this->save();

        return $this;
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getTotalAttribute()
    {
        $total = 0.0;
        if ($this->details !== null) {
            foreach ($this->details as $detail) {
                if ($detail->model !== null) {
                    $total += $detail->quantity * $detail->price;
                }
            }
        }

        return round($total, 2);
    }

    public function getDepositTotalAttribute()
    {
        $depositRate  = $this->user->business->getDepositRate();
        $depositTotal = $this->total * $depositRate;

        return round($depositTotal, 2);
    }

    public function getTotalItemsAttribute()
    {
        if ($this->details !== null) {
            return array_sum($this->details->lists('quantity'));
        }

        return 0;
    }

    public function getIsDepositPaymentAttribute()
    {
        return isset($this->attributes['is_deposit_payment'])
            ? (bool) $this->attributes['is_deposit_payment']
            : false ;
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

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeCompleted($query)
    {
        return $query->where('status', static::STATUS_COMPLETED);
    }
}
