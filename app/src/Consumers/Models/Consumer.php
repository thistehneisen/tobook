<?php namespace App\Consumers\Models;

use Confide, DB;
use App\Core\Models\User;

class Consumer extends \App\Core\Models\Base
{
    public $fillable = [
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
            'email'         => 'required|email',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setEmailAttribute($value)
    {
        $value = trim($value);
        $this->attributes['email'] = $value;
    }

    public function setPhoneAttribute($value)
    {
        //Remove + and spaces since phone is numberic value
        $value =  str_replace([' ', '+'], '', $value);
        $this->attributes['phone'] = $value;
    }

    private function checkService($table)
    {
        $service = DB::table($table)
                    ->where('user_id', Confide::user()->id)
                    ->where('consumer_id', $this->id)
                    ->first();

        if ($service) {
            return true;
        } else {
            return false;
        }
    }

    public function getServiceAttribute()
    {
        $service = [];

        $services = [
            'as_consumers' => trans('dashboard.appointment'),
            'lc_consumers' => trans('dashboard.loyalty'),
        ];

        foreach ($services as $key => $value) {
            if ($this->checkService($key)) {
                $service[substr($key, 0, 2)] = $value;
            }
        }

        return $service;
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * Create a new consumer
     *
     * @param array                    $data   Consumer's information will be saved
     * @param int|App\Core\Models\User $userId The ID of user this consumer
     *                                         belongs to
     *
     * @throws Watson\Validating\ValidationException If validation is not passed
     * @throws App\Consumers\DuplicatedException     If there is existing consumer with the same email
     * @throws Illuminate\Database\QueryException    If there are database errors
     *
     * @return App\Consumers\Model
     */
    public static function make($data, $userId = null)
    {
        // If there is no information passed, get the current user
        $user = Confide::user();
        if ($userId instanceof \App\Core\Models\User) {
            $user = $userId;
        } elseif ((int) $userId > 0) {
            $user = User::findOrFail($userId);
        }

        if (empty($data['email'])) {
            $data['email'] = '';
        }

        try {
            $consumer = new self();
            $consumer->fill($data);
            $consumer->saveOrFail();
        } catch (\Illuminate\Database\QueryException $ex) {
            // Check if there's existing consumer with the same email
            $consumer = self::where('email', $data['email'])->first();
            if ($consumer !== null) {
                $ex = new \App\Consumers\DuplicatedException('There is already a consumer with email '.$data['email']);
                $ex->setDuplicated($consumer);
                throw $ex;
            }
            // Maybe other database erorrs, so we pass it to other handlers
            throw $ex;
        }

        $user->consumers()->attach($consumer->id, ['is_visible' => true]);

        return $consumer;
    }

    /**
     * Hide this consumer
     *
     * @param int $userId ID of user whom this consumer will be hidden from
     *
     * @return Consumer
     */
    public function hide($userId)
    {
        return $this->users()
            ->updateExistingPivot($userId, [
                'is_visible' => false
            ]);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    /**
     * Define a many-to-many relationship to User
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Core\Models\User')
            ->withPivot('is_visible');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeVisible($query)
    {
        return $query->whereHas('users', function ($q) {
            return $q->where('is_visible', true);
        });
    }
}
