<?php namespace App\Core\Models;

use App, DB, Hashids, Config, Carbon\Carbon, Geocoder, Util;
use Consumer, Log, NAT;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use App\Search\SearchableInterface;
use App\Search\ElasticSearchTrait;

class User extends ConfideUser implements SearchableInterface
{
    use SoftDeletingTrait;
    use HasRole;
    use ElasticSearchTrait;

    public $visible = [
        'id',
        'email',
    ];

    public $fillable = [
        'email',
    ];

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = true;

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
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        if (isset(ConfideUser::$rules['username'])) {
            unset(ConfideUser::$rules['username']);
        }
    }

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
        // $slots = NAT::nextUser($this, $date);
        // if ($slots->isEmpty() === false) {
        //     return $slots->toArray();
        // }

        // for ($i = 0; $i < 3; $i++) {
        //     $slots = CalendarKeeper::nextTimeSlots($this, $date, $nextHour, $nextService);
        //     if (empty($slots)) {
        //         $date->addDay();
        //     } else {
        //         break;
        //     }
        // }
        // return $slots;
        return NAT::nextUser($this, $date)->toArray();
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
        return $this->belongsToMany('App\Consumers\Models\Consumer');
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
     * @param array $consumerData
     *
     * @return void
     */
    public function attachConsumer(array $consumerData = [])
    {
        // @todo: More criteria to check existing consumer
        $consumer = Consumer::where('email', $this->email)->first();

        if (empty($consumer)) {
            $consumer = new Consumer();
            $consumer->email = $this->email;
            $consumer->fill($consumerData);
            $consumer->saveOrFail();
        }

        $this->consumer()->associate($consumer);
    }

    /**
     * Update activated modules for user
     *
     * @param array $activatedModules
     *
     * @return App\Core\Models\User
     */
    public function updateDisabledModules($activatedModules)
    {
        $all = array_keys(Config::get('varaa.premium_modules'));
        $disabled = array_diff($all, $activatedModules);

        // Remove all existing disabled modules
        $this->disabledModules()->delete();
        foreach ($disabled as $name) {
            $module = new DisabledModule(['module' => $name]);
            $this->disabledModules()->save($module);
        }

        return $this;
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
        if (!empty($this->asOptionsCache)) {
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
     * Check to see if this user is an admin
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->hasRole(Role::ADMIN);
    }

    //--------------------------------------------------------------------------
    // SEARCH
    //--------------------------------------------------------------------------

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocument()
    {
        $data = ['email' => $this->email];

        // Check if this user is a business user
        // Pull out business information
        if ($this->is_business && $this->business !== null) {
            $data['business'] = $this->business->name;
        } elseif ($this->is_consumer) {
            $data['consumer'] = $this->consumer->name;
        }

        return $data;
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchMapping()
    {
        return [
            'email'    => ['type' => 'string'],
            'business' => ['type' => 'string'],
            'consumer' => ['type' => 'string'],
        ];
    }

    /**
     * @{@inheritdoc}
     */
    protected function buildSearchQuery($keyword, $fields = null)
    {
        $query = [];
        $query['bool']['should'][]['match']['email'] = $keyword;
        $query['bool']['should'][]['match']['business'] = $keyword;

        return $query;
    }

    /**
     * @{@inheritdoc}
     */
    public function transformSearchResult($results)
    {
        $data = [];
        foreach ($results as $result) {
            $item = static::find($result['_id']);
            if ($item !== null && $item->is_business) {
                $data[] = $item;
            }
        }

        return $data;
    }
}
