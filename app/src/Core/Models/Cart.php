<?php namespace App\Core\Models;
use Confide;
use Carbon\Carbon;
use App\Core\Models\User;


class Cart extends Base
{
    public $fillable = ['status'];

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

        try {
            $cart = new self();
            $cart->fill($data);
            $cart->user()->associate($user);
            if (!empty($consumer)) {
                $detail->consumer()->associate($consumer);
            }
            $cart->saveOrFail();
        } catch (\Illuminate\Database\QueryException $ex) {
            throw $ex;
        }

        return $cart;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
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
