<?php namespace App\Core\Models;

use App, DB, Hashids, Config, Carbon\Carbon, Geocoder;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends ConfideUser
{
    const IMAGEABLE_PATH = '/users';

    use SoftDeletingTrait;
    use HasRole;

    public $visible = [
        'id',
        'username',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
    ];

    public $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'postcode',
        'country',
        'description',
        'business_size',
        'business_name',
        'lat',
        'lng'
    ];

    /**
     * List of enabled premium modules for this user
     *
     * @var array
     */
    protected $enabledModules;

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        // Whenever updating account, we will try to find geocode of this user
        static::updating(function($user) {
            $user->updateGeo();
        });
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

    public function businessCategories()
    {
        return $this->belongsToMany('App\Core\Models\BusinessCategory');
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
        return $this->hasManyThrough(
            'App\FlashDeal\Models\Coupon',
            'App\FlashDeal\Models\Service'
        );
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
     * Return extra action links that are displayed in admin CRUD list
     *
     * @return array
     */
    public function getExtraActionLinks()
    {
        return [
            '<i class="fa fa-user"></i> Login' => route('admin.users.login', ['id' => $this->id]),
            '<i class="fa fa-puzzle-piece"></i> Modules' => route('admin.users.modules', ['id' => $this->id])
        ];
    }

    /**
     * Sync valid Business Categories for this user
     *
     * @param array $ids A list of business category IDs
     *
     * @return void
     */
    public function updateBusinessCategories($ids)
    {
        $all = BusinessCategory::all()->lists('id');
        $ids = array_intersect($all, $ids);
        $this->businessCategories()->sync($ids);
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
     * Update latitude and longitude of the current address
     *
     * @return void
     */
    public function updateGeo()
    {
        $new = $this->full_address;
        $old = $this->getFullAddress(
            $this->getOriginal('address'),
            $this->getOriginal('postcode'),
            $this->getOriginal('city'),
            $this->getOriginal('country')
        );

        if (!empty($new) && $new !== $old) {
            try {
                $geocode = Geocoder::geocode($new);
                $this->attributes['lat'] = $geocode->getLatitude();
                $this->attributes['lng'] = $geocode->getLongitude();
            } catch (\Exception $ex) {
                // Silently fail
            }
        }
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

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

        return new \Illuminate\Support\Collection($ret);
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
     * Generate full address based on address fragments
     *
     * @param string $address
     * @param string $postcode
     * @param string $city
     * @param string $country
     *
     * @return string
     */
    protected function getFullAddress($address, $postcode, $city, $country)
    {
        return sprintf('%s, %s %s, %s', $address, $postcode, $city, $country);
    }

    /**
     * Return the full address of this user
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return $this->getFullAddress(
            $this->attributes['address'],
            $this->attributes['postcode'],
            $this->attributes['city'],
            $this->attributes['country']
        );
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }
}
