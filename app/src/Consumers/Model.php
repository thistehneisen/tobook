<?php namespace App\Consumers;

class Model extends \App\Core\Models\Base
{
    protected $table = 'consumers';

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
     * Define a many-to-many relationship to User
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Core\Models\User');
    }

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
}
