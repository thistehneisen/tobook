<?php namespace App\Core\Models;

use App, DB, Hashids, Config, Carbon\Carbon, Geocoder, Util;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Consumer;
use App\Appointment\Models\NAT\CalendarKeeper;

class User extends ConfideUser
{
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
        'username',
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
     *
     * @return array
     */
    public function getASDefaultWorkingTimes($date)
    {
        return CalendarKeeper::getDefaultWorkingTimes($this, $date);
    }

    /**
     * Get a number of random users
     *
     * @param int categoryId
     * @param int quantity
     *
     * @return Illuminate\Support\Collection
     */
    public static function getRandomUser($categoryId, $quantity)
    {
        //It is not efficient to order by RAND() but we have relatively small customers base
        $users = self::orderBy(DB::raw('RAND()'))
            ->join('business_category_user', 'business_category_user.user_id', '=','users.id')
            ->where('business_category_user.business_category_id', $categoryId)
            ->where('business_name', '!=', '')
            ->limit($quantity)->get();
        return $users;
    }

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        // Whenever updating account, we will try to find geocode of this user
        static::updating(function($user) {
            $user->updateGeo();
            return true;
        });
    }

    /**
     * Overwrite this magic method, so that consumer user will return the correct
     * value instead
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        // The list of share attributes between a normal user and a consumer
        $details = [
            'first_name' => true,
            'last_name'  => true,
            'email'      => true,
            'phone'      => true,
            'address'    => true,
            'city'       => true,
            'postcode'   => true,
            'country'    => true,
        ];

        // If the requested key is not in the whitelist
        if (!isset($details[$key])) {
            // We'll just need to return it
            return parent::__get($key);
        }

        // Otherwise, check if this user is a consumer user
        $consumer = $this->consumer;
        if ($consumer !== null) {
            // Return the consumer value instead
            return $consumer->$key;
        }

        // For users of other roles, return as usual
        return parent::__get($key);
    }

    /**
     * Old function to search by using SQL like
     *
     * @return
     */
    public static function search($q, $location)
    {
        $query = with(new self())->newQuery();
        if (!empty($q)) {
            $queryString = '%'.$q.'%';
            $query = $query->whereHas(
                'businessCategories',
                function ($query) use ($queryString) {
                    return $query->where('name', 'LIKE', $queryString)
                        ->orWhere('keywords', 'LIKE', $queryString);
                }
            )->orWhere('business_name', 'LIKE', $queryString);
        }

        if (!empty($location)) {
            $query = $query->where('city', 'LIKE', '%'.$location.'%');
        }

        $businesses = $query
            ->where('business_name', '!=', '')
            ->paginate(Config::get('view.perPage'));

        return $businesses;
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
                \Log::error($ex->getMessage(), ['context' => 'Update user profile']);
            }
        }
    }


    /**
     * Check if there is an existing consumer having the same info with
     * submitted data. If yes, connect them together.
     *
     * @return
     */
    public function validateExistingConsumer()
    {
        // @todo: More criteria to check existing consumer
        $consumer = Consumer::where('email', $this->attributes['email'])->first();
        if ($consumer !== null) {
            $this->consumer()->associate($consumer);
        }
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------


    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
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

    public function getCountryList()
    {
        return [ trans("common.select") ,"Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];
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


    public function getImageAttribute()
    {
        foreach ($this->businessCategories as $cat) {
            switch ($cat->name) {
                case 'Beauty &amp; Hair':
                    return '/assets/img/categories/hair/7.jpg';

                case 'Hairdresser':
                    return '/assets/img/categories/hair/2.jpg';

                case 'Restaurant':
                    return '/assets/img/categories/restaurant/1.jpg';
                    break;

                case 'Fine Dining':
                    return '/assets/img/categories/restaurant/8.jpg';

                case 'Car Wash':
                    return '/assets/img/categories/carwash/2.jpg';

                case 'Activities':
                    return '/assets/img/categories/fitness/1.jpg';

                default:
                    break;
            }
        }
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
}
