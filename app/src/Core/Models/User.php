<?php namespace App\Core\Models;

use App, DB, Hashids, Config, Carbon\Carbon, Geocoder, Util;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Consumer;
use App\Appointment\Models\NAT\CalendarKeeper;
use App\Search;

class User extends ConfideUser
{
    use SoftDeletingTrait;
    use HasRole;

    public $visible = [
        'id',
        'email',
    ];

    public $fillable = [
        'email',
    ];

    /**
     * List of enabled premium modules for this user
     *
     * @var array
     */
    protected $enabledModules;

    /**
     * Cache object to store colletion of as_options to avoid multiple sql queries
     *
     * @var Collection
     */
    protected $asOptionsCache;

    /**
     * Get next avaiable booking slots of current user
     *
     * @param string date
     * @param int nextHour
     * @param Service nextService
     *
     * @return array
     */
    public function getASNextTimeSlots($date = null, $nextHour = null, $nextService = null)
    {
        for ($i = 0; $i < 3; $i++) {
            $slots = CalendarKeeper::nextTimeSlots($this, $date, $nextHour, $nextService);
            if (empty($slots)) {
                $date->addDay();
            } else {
                break;
            }
        }
        return $slots;
    }


    /**
     * Get default working time range of Appointment Scheduler
     *
     * @param string date
     * @param boolean showUntilLastestBooking : show end time up to lastest booking end time in a day
     *
     * @return array
     */
    public function getASDefaultWorkingTimes($date, $showUntilLastestBooking = true)
    {
        return CalendarKeeper::getDefaultWorkingTimes($this, $date, $showUntilLastestBooking);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function disabledModules()
    {
        return $this->hasMany('App\Core\Models\DisabledModule');
    }

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

    public function images()
    {
        return $this->morphMany('App\Core\Models\Image', 'imageable');
    }

    public function coupons()
    {
        return $this->hasMany('App\FlashDeal\Models\Coupon');
    }

    public function flashDeals()
    {
        return $this->hasMany('App\FlashDeal\Models\FlashDealDate');
    }

    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }

    public function business()
    {
        return $this->hasOne('App\Core\Models\Business');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

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
        $_SESSION['owner_id']          = $this->id;
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
     * Check if this user has the given module enabled
     *
     * @param string $moduleName
     *
     * @return boolean
     */
    public function hasModule($moduleName)
    {
        return isset($this->modules[$moduleName]);
    }


    /**
     * Check if there is an existing consumer having the same info with
     * submitted data. If yes, connect them together. Otherwise, create a new
     * consumer.
     *
     * @return void
     */
    public function attachConsumer()
    {
        // @todo: More criteria to check existing consumer
        $consumer = Consumer::where('email', $this->attributes['email'])->first();
        if ($consumer === null) {
            $consumer = new Consumer([
                'email'      => $this->attributes['email'],
                'first_name' => !empty($this->attributes['first_name']) ? $this->attributes['first_name'] : '',
                'last_name'  => !empty($this->attributes['last_name']) ? $this->attributes['last_name'] : '',
                'phone'      => !empty($this->attributes['phone']) ? $this->attributes['phone'] : '',
            ]);
            $consumer->saveOrFail();
        }

        $this->consumer()->associate($consumer);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getBusinessUrlAttribute()
    {
        return route('business.index', ['id' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * Get all options of this user
     *
     * @return object
     */
    public function getAsOptionsAttribute()
    {
        if(!empty($this->asOptionsCache)){
            return $this->asOptionsCache;
        }

        $ret = [];
        $default = Config::get('appointment.options');

        // Get options from database
        $customOptions = [];
        foreach ($this->asOptions()->get() as $item) {
            $customOptions[$item->key] = $item->value;
            $ret[$item->key] = $item->value;
        }

        foreach ($default as $sections) {
            foreach ($sections as $options) {
                foreach ($options as $name => $option) {

                    $ret[$name] = isset($option['default'])
                        ? $option['default']
                        : '';

                    // Overwrite data from database
                    if (isset($customOptions[$name])) {
                        $ret[$name] = $customOptions[$name];
                    }
                }
            }
        }

        $this->asOptionsCache = new \Illuminate\Support\Collection($ret);

        return $this->asOptionsCache;
    }

    /**
     * Return the hash of this user, useful for generate embeded URLs
     *
     * @return string
     */
    public function getHashAttribute()
    {
        return Hashids::encrypt($this->attributes['id']);
    }

    /**
     * Return a list of enabled premium modules
     *
     * @return void
     */
    public function getModulesAttribute()
    {
        if ($this->enabledModules === null) {
            $modules = [];

            $disabled = $this->disabledModules->lists('module');

            // Get all config first
            $all = Config::get('varaa.premium_modules');
            foreach ($all as $name => $value) {
                if ($value['enable'] === true && !in_array($name, $disabled)) {
                    $modules[$name] = $value['route_name'];
                }
            }

            $this->enabledModules = $modules;
        }

        return $this->enabledModules;
    }

    /**
     * Check to see if this user is a business
     *
     * @return bool
     */
    public function getIsBusinessAttribute()
    {
        return $this->hasRole(Role::USER);
    }

    /**
     * Check to see if this user is a consumer
     *
     * @return bool
     */
    public function getIsConsumerAttribute()
    {
        return $this->hasRole(Role::CONSUMER);
    }

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        if (isset(ConfideUser::$rules['username'])) {
            unset(ConfideUser::$rules['username']);
        }
    }
}
