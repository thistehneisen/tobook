<?php namespace App\Core\Models;

use Confide;
use Carbon\Carbon;
use App\Core\Models\User;
use Illuminate\Support\Collection;

class Cart extends Base
{
    public $fillable = ['status', 'notes'];

    const STATUS_INIT          = 1;//01
    const STATUS_COMPLETED     = 2;//11

    /**
     * Create a new cart item
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     * @throws Illuminate\Database\QueryException    If there are database errors
     *
     * @return App\Core\Model\Cart
     */
    public static function make($data, $userId = null, $consumer = null)
    {
        $user = Confide::user();
        if ($userId instanceof \App\Core\Models\User) {
            $user = $userId;
        } elseif ((int) $userId > 0) {
            $user = User::findOrFail($userId);
        }

        if ($user === null) {
            throw new \InvalidArgumentException(
                'A cart must be associated with an user'
            );
        }

        $cart = new self();
        $cart->fill($data);
        $cart->user()->associate($user);
        if (!empty($consumer)) {
            $detail->consumer()->associate($consumer);
        }
        $cart->saveOrFail();

        return $cart;
    }

    /**
     * Add a detail item into cart
     *
     * @param array $data
     */
    public function addDetail(array $data)
    {
        $detail = new CartDetail($data);
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

    /**
     * Return the total amount of all items in cart
     *
     * @return double
     */
    public function getSubtotalAttribute()
    {
        $subtotal = 0.0;

        foreach ($this->details as $detail) {
            $subtotal += $detail->quantity * $detail->price;
        }

        return $subtotal;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    /**
     * Alias of cartDetails()
     */
    public function details()
    {
        return $this->cartDetails();
    }

    public function cartDetails()
    {
        return $this->hasMany('App\Core\Models\CartDetail');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
