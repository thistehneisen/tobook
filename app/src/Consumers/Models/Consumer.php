<?php namespace App\Consumers\Models;

use Confide;
use App\Core\Models\User;

class Consumer extends \App\Core\Models\Base
{
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
            'email' => 'required|email',
        ]
    ];

    /**
     * Define a many-to-many relationship to User
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Core\Models\User');
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

    /**
     * Create a new consumer
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     * @throws Illuminate\Database\QueryException If the email is used
     *
     * @return App\Consumers\Model
     */
    public static function make($data, $userId = null)
    {
        // If there is no information passed, get the current user
        $user = Confide::user();
        if ((int) $userId >  0) {
            $user = User::findOrFail($userId);
        }

        $consumer = new self;
        $consumer->fill($data);
        $consumer->saveOrFail();

        $user->consumers()->attach($consumer->id, ['is_visible' => true]);

        return $consumer;
    }
}
