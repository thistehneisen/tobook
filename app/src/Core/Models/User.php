<?php namespace App\Core\Models;

use App, DB, Config;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser
{
    use HasRole;

    public $visible = [
        'id',
        'username',
        'email',
        'first_name',
        'last_name',
        'phone',
        /*'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',*/
    ];

    /**
     * Allow old users to login with their own password, but force to change
     * immediately
     *
     * @param string $identity Email/username
     * @param string $password Plain password
     *
     * @return bool
     */
    public static function oldLogin($identity, $password)
    {
        $user = User::where(function ($query) use ($identity) {
            return $query->where('username', '=', $identity)
                ->orWhere('email', '=', $identity);
        })
        ->where('old_password', '=', md5($password))
        ->first();

        if ($user) {
            // Manually login
            App::make('auth')->login($user);

            return $user;
        }

        return false;
    }

    /**
     * Dump current user to native PHP session for other modules
     *
     * @return void
     */
    public function dumpToSession()
    {
        @session_start();
        $_SESSION['session_loginname'] = $this->username;
        $_SESSION['session_userid']    = $this->id;
        $_SESSION['session_email']     = $this->email;
        $_SESSION['session_style']     = $this->stylesheet;
        $_SESSION["owner_id"]          = $this->id;
    }

    /**
     * Remove old password to prevent login with this again
     *
     * @return bool
     */
    public function removeOldPassword()
    {
        $this->old_password = null;
        $this->save();
    }

    /**
     * Check if this user has activated a module / service
     *
     * @return boolean
     */
    public function isServiceActivated($tablePrefix, $table)
    {
        $oldPrefix = DB::getTablePrefix();
        DB::setTablePrefix($tablePrefix);

        $user = DB::table($table)->where('owner_id', '=', $this->id)->first();

        DB::setTablePrefix($oldPrefix);

        return (bool) $user;
    }

    /**
     * Return extra action links that are displayed in admin CRUD list
     *
     * @return array
     */
    public function getExtraActionLinks()
    {
        return [
            '<i class="fa fa-user"></i> Login' => route('admin.users.login', ['id' => $this->id])
        ];
    }

    /**
     * Get all options of this user
     *
     * @return object
     */
    public function getAsOptionsAttribute()
    {
        $ret = [];
        $default = Config::get('appointment.options');

        // Get options from database
        $customOptions = [];
        foreach ($this->asOptions()->get() as $item) {
            $customOptions[$item->key] = $item->value;
        }

        foreach ($default as $sections) {
            foreach ($sections as $options) {
                foreach ($options as $name => $option) {
                    if (isset($ret[$name])) {
                        throw new \Exception("There is existing keys of [$name]");
                    }

                    $ret[$name] = isset($option['default'])
                        ? $option['default']
                        : true;

                    // Overwrite data from database
                    if (isset($customOptions[$name])) {
                        $ret[$name] = $customOptions[$name];
                    }
                }
            }
        }
        return new \Illuminate\Support\Collection($ret);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    /**
     * Define a many-to-many relationship to App\Consumers\Models\Consumer
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function consumers()
    {
        return $this->belongsToMany('App\Consumers\Models\Consumer')
            ->withPivot('is_visible');
    }

    public function asServiceCategories()
    {
        return $this->hasMany('App\Appointment\Models\ServiceCategory');
    }

    public function asOptions()
    {
        return $this->hasMany('App\Appointment\Models\Option');
    }
}
