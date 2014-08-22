<?php namespace App\Core\Models;
use Watson\Validating\ValidatingTrait;

class Consumer extends \Eloquent
{
    use ValidatingTrait;

    public $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
        'country',
    ];

    protected $rulesets = [
        'saving' => [
            'user_id'    => 'required',
            'email'      => 'required|email',
        ]
    ];

    /**
     * Concat first_name and last_name
     * Usage: $user->name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * This consumer could be a consumer of module Loyalty Card
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loyalty()
    {
        return $this->hasOne('App\LoyaltyCard\Models\Consumer');
    }

    /**
     * Create a core consumer, so that developers don't need to type the long
     * namespace path.
     *
     * @return App\Core\Models\Consumer
     */
    public static function createCore()
    {
        return new self;
    }
}
