<?php namespace App\Core\Models;

use App;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Service;
use App\Appointment\Models\Discount\DiscountBusiness;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
use App\Core\Models\Relations\BusinessBusinessCategory;
use App\Haku\Indexers\BusinessIndexer;
use App\Core\Models\Review;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Input;
use NAT;
use Settings;
use Str;
use Util;

class Business extends Base
{
    use DiscountBusiness;
    use App\Search\ElasticSearchTrait;
    
    private $mostDiscountedService = null;

    public $fillable = [
        'name',
        'phone',
        'description',
        'size',
        'address',
        'district',
        'city',
        'postcode',
        'country',
        'is_booking_disabled',
        'payment_methods',
        'lat',
        'lng',
        'bank_account',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_hidden',
        'note',
        'payment_options',
        'deposit_rate',
        'disabled_payment',
    ];

    public $rulesets = [
        'saving' => [
            'name' => 'required',
            'phone' => 'required',
        ]
    ];

    public $hidden = [
        'note',
        'bank_account',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_hidden',
        'user_id',
        'is_activated',
        'is_booking_disabled',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'full_address',
        'hash',
    ];

    /**
     * Use to store data from table multilang
     *
     * @var array
     */
    protected $multilang = [];

    /**
     * If user search for a specific location
     * the result should show the most matches first
     *
     * @var boolean
     */
    public $isSearchByLocation = false;

    /**
     * Use for sorting the results based on keyword in case of location
     *
     * @var string
     */
    public $keyword = '';

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        // Whenever saving account, we will try to find geocode of this business
        static::updating(function ($business) {
            $business->updateGeo();

            return true;
        });

        static::saved(function ($model) {
            $model->updateIndex();
        });

        static::deleted(function ($model) {
            $i = new BusinessIndexer($model);
            $i->delete();
        });

        static::restored(function ($model) {
            $model->updateIndex();
        });
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    /**
     * Return businesses of a specific category
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param int                                  $categoryId
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCategory($query, $categoryId)
    {
        return $query->has('businessCategories', $categoryId);
    }

    /**
     * Return businesses that are not hidden
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotHidden($query)
    {
        return $query->where('is_hidden', 0);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function businessCategories()
    {
        $relation = $this->getBelongsToManyCaller();
        $instance = new BusinessCategory();
        $query = $instance->newQuery();

        // use our custom built relation because the `user_id` must be used instead of `id`
        // TODO `migrate business_category_user` to `business_business_category`?
        return new BusinessBusinessCategory($query,
            $this,
            'business_category_user',
            'user_id',
            'business_category_id',
            $relation);
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    /**
     * Check if a payment option is enabled
     *
     * @param string $option
     *
     * @return boolean
     */
    public function isPaymentOptionEnabled($option)
    {
        $opts = $this->payment_options;

        return in_array($option, $opts !== null ? $opts : []);
    }

    /**
     * Update latitude and longitude of the current address
     *
     * @return void
     */
    public function updateGeo()
    {
        try {
            list($lat, $lng) = Util::geocoder($this->full_address);
            $this->attributes['lat'] = $lat;
            $this->attributes['lng'] = $lng;
        } catch (Exception $ex) {
            // Silently fail
        }
    }

    /**
     * Sync valid Business Categories for this business
     *
     * @param array $ids A list of business category IDs
     *
     * @return void
     */
    public function updateBusinessCategories($ids)
    {
        if (is_array($ids)) {
            $all = BusinessCategory::all()->lists('id');
            $ids = array_intersect($all, $ids);
            $this->businessCategories()->sync($ids);
        }
    }

    /**
     * Update business description in multiple languages
     *
     * @param array $input
     *
     * @return App\Core\Models\Business
     */
    public function updateDescription(array $input)
    {
        return $this->updateMultiligualAttribute('business_description', $input);
    }

    /**
     * Update attribute that supports multilanguage
     *
     * @param string $attr
     * @param array  $input
     *
     * @return App\Core\Models\Business
     */
    public function updateMultiligualAttribute($attr, array $input)
    {
        $key = $attr;
        $input = (array) $input;

        foreach ($input as $lang => $value) {
            $obj = Multilanguage::where('user_id', $this->user_id)
                ->where('context', $this->getTable())
                ->where('key', $key)
                ->where('lang', $lang)
                ->first();
            if ($obj === null) {
                $obj = new Multilanguage();
            }

            $obj->fill([
                'context' => $this->getTable(),
                'lang'    => $lang,
                'key'     => $key,
                'value'   => $value,
            ]);
            $obj->user()->associate($this->user);
            $obj->save();
        }

        return $this;
    }

    /**
     * Update information of this business
     *
     * @param array                $input
     * @param App\Core\Models\User $user
     *
     * @return Business
     */
    public function updateInformation($input, $user)
    {
        $this->fill([
            'name'                => array_get($input, 'name', ''),
            'size'                => array_get($input, 'size', ''),
            'address'             => array_get($input, 'address', ''),
            'district'            => array_get($input, 'district', ''),
            'city'                => array_get($input, 'city', ''),
            'postcode'            => array_get($input, 'postcode', ''),
            'country'             => array_get($input, 'country', ''),
            'phone'               => array_get($input, 'phone', ''),
            'is_booking_disabled' => array_get($input, 'is_booking_disabled', true),
            'note'                => array_get($input, 'note', ''),
            'is_hidden'           => array_get($input, 'is_hidden', false),
            'payment_methods'     => array_get($input, 'payment_methods', ''),
        ]);
        $this->user()->associate($user);
        $this->saveOrFail();

        // Update description
        if (isset($input['description_html'])) {
            $this->updateDescription($input['description_html']);
        }
        foreach (['meta_title', 'meta_keywords', 'meta_description'] as $field) {
            if (isset($input[$field])) {
                $this->updateMultiligualAttribute($field, $input[$field]);
            }
        }

        if (!empty($input['categories'])) {
            $this->updateBusinessCategories($input['categories']);
        }

        return $this;
    }

    /**
     * Return the list of all countries in the world
     *
     * @return arrray
     */
    public function getCountryList()
    {
        return [trans("common.select"), "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];
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
        return $address ? sprintf('%s, %s %s, %s', $address, $postcode, $city, $country) : '';
    }

    /**
     * Get business description in a specific language
     *
     * @param string $language
     *
     * @return string
     */
    public function getDescriptionInLanguage($language)
    {
        return $this->getAttributeInLanguage('business_description', $language);
    }

    /**
     * Get custom deposit rate of this business
     * If not set, use the system default
     *
     * @return double
     */
    public function getDepositRate()
    {
        if (empty($this->deposit_rate)) {
            return (double) Settings::get('deposit_rate');
        }

        return $this->deposit_rate;
    }

    /**
     * Return if this business has been disabled payment options
     *
     * @return bool
     */
    public function getDisabledPaymentAttribute()
    {
        return (bool) array_get($this->attributes, 'disabled_payment', false);
    }

    /**
     * Get value of an attribute in multilanguages
     *
     * @param string $attribute
     * @param string $language
     *
     * @return mixed
     */
    public function getAttributeInLanguage($attribute, $language)
    {
        $key = $attribute;
        if (!isset($this->multilang[$key])) {
            $results = Multilanguage::where('context', $this->getTable())
                ->where('key', $key)
                ->where('user_id', $this->user_id)
                ->get();

            foreach ($results as $item) {
                $this->multilang[$key][$item->lang] = $item->value;
            }
        }

        return (isset($this->multilang[$key][$language]))
            ? $this->multilang[$key][$language]
            : '';
    }

    /**
     * Get all non-hidden businesses
     *
     * @return Illuminate\Pagination\Paginator
     */
    public static function getAll()
    {
        return static::notHidden()
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('user.images')
            ->paginate();
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getPaymentOptionsAttribute()
    {
        $opts = array_get($this->attributes, 'payment_options');

        $default = (App::environment() === 'tobook') ? ['full'] : ['venue'];

        return $opts ? json_decode($opts, true) : $default;
    }

    public function getPaymentMethodsAttribute()
    {
        $opts = array_get($this->attributes, 'payment_methods');

        $default = [];

        return $opts ? json_decode($opts, true) : $default;
    }

    public function setPaymentOptionsAttribute($value)
    {
        $this->attributes['payment_options'] = json_encode($value);
    }

    public function setPaymentMethodsAttribute($value)
    {
        $this->attributes['payment_methods'] = json_encode($value);
    }

    public function getWorkingHoursArrayAttribute()
    {
        $default = [
            'mon' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'tue' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'wed' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'thu' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'fri' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'sat' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
            'sun' => ['start' => '08:00', 'end' => '20:00', 'extra' => ''],
        ];

        $workingHours = !empty($this->attributes['working_hours'])
            ? json_decode($this->attributes['working_hours'], true)
            : $default;

        $data = [];
        foreach ($workingHours as $day => $attributes) {
            $attributes['formatted']  = sprintf('%s &ndash; %s', $attributes['start'], $attributes['end']);

            try {
                $start = with(new Carbon($attributes['start']))->format('H:i');
                $end = with(new Carbon($attributes['end']))->format('H:i');
                $attributes['formatted']  = sprintf('%s &ndash; %s', $start, $end);
            } catch (\Exception $ex) {
                // In case we have malformed working hours value
                // Silently failed
            }

            $data[$day] = $attributes;
        }

        return $data;
    }

    public function setWorkingHoursAttribute($value)
    {
        $this->attributes['working_hours'] = json_encode($value);
    }

    public function getIsHiddenAttribute()
    {
        return isset($this->attributes['is_hidden'])
            ? (bool) $this->attributes['is_hidden']
            : false ;
    }

    public function getTotalCommissionAttribute()
    {
        $value = App::make('\App\Appointment\Models\Booking')
            ->calculateCommissions($this->user);

        return $value > 0 ? number_format($value, 2) : null;
    }

    public function getPaidCommissionAttribute()
    {
        $model = App::make('\App\Core\Models\CommissionLog');
        $value = $model->calculatePaid($this->user);

        return number_format($value, 2);
    }

    /**
     * Return the full address of this user
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        if (empty($this->address)) {
            return '';
        }

        if (!empty($this->district)) {
            return sprintf('%s, %s, %s %s, %s',
                $this->address,
                $this->district,
                $this->postcode,
                $this->city,
                $this->country);
        }

        return sprintf('%s, %s %s, %s',
                $this->address,
                $this->postcode,
                $this->city,
                $this->country);
    }

    /**
     * Shortcut to get all images of this business
     *
     * @return Illuminate\Support\Collection
     */
    public function getImagesAttribute()
    {
        return $this->user->images()->businessImages()->get();
    }

    /**
     * Get an image from the list of business image
     *
     * @return string
     */
    public function getImageAttribute()
    {
        $image = $this->images->first();
        if (!empty($image)) {
            return asset(Config::get('varaa.upload_folder').$image->path);
        } else {
            $imageMap = [
                'beauty_hair' => asset_path('core/img/categories/beauty/beauty1.jpg'),
                'hairdresser' => asset_path('core/img/categories/hair/hair1.jpg'),
                'restaurant'  => asset_path('core/img/categories/restaurant/res2.jpg'),
                'fine_dining' => asset_path('core/img/categories/restaurant/res8.jpg'),
                'car_wash'    => asset_path('core/img/categories/carwash/car1.jpg'),
                'activities'  => asset_path('core/img/categories/fitness/fitness1.jpg'),
            ];

            foreach ($this->businessCategories as $cat) {
                return isset($imageMap[$cat->name])
                    ? $imageMap[$cat->name]
                    : asset_path('core/img/categories/beauty/beauty1.jpg');
            }
        }
    }

    public function getBusinessUrlAttribute()
    {
        return route('business.index', ['id' => $this->user_id, 'slug' => $this->slug]);
    }

    public function getDescriptionAttribute()
    {
        // this method was put as a safe guard against naive usage of the attribute
        // for full html description, caller must use getDescriptionHtml()
        return $this->getDescriptionPlainAttribute();
    }

    /**
     * Get description as html
     *
     * @return string
     */
    public function getDescriptionHtmlAttribute()
    {
        return $this->getTranslatedAttribute('business_description');
    }

    public function getMetaTitleAttribute()
    {
        return $this->getTranslatedAttribute('meta_title');
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('meta_description');
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->getTranslatedAttribute('meta_keywords');
    }

    public function getTitleAttribute()
    {
        return sprintf('%s :: %s', $this->name, Settings::get('meta_title'));
    }

    /**
     * Count number of reviews for this business
     * 
     * @return int
     */
    public function getReviewCountAttribute()
    {
        return $this->user->reviews->count();
    }

    /**
     * Get maximum discount percentage of current business
     * 
     * @return integer
     */
    public function getDiscountPercentAttribute()
    {
        //$originalPrice = Service::where('user_id', '=', $this->user_id)->max('price');

        $discount1  = Discount::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)->max('discount');
        $discount1 = (!empty($discount1)) ? $discount1 : 0;

        $discount2 = DiscountLastMinute::where('user_id', '=', $this->user->id)
            ->where('is_active', '=', true)->first();
        $discount2 = (!empty($discount2->discount)) ? $discount2->discount : 0;

    
        $discount = ($discount1 > $discount2) ? $discount1 : $discount2;

        return  $discount;
    }

    /**
     * Retrieve the average rating score of current business
     * 
     * @return double
     */
    public function getReviewScoreAttribute()
    {
        $review = Review::where('user_id', '=', $this->user->id)->where('status', '=', Review::STATUS_APPROVED)
            ->select(DB::raw("AVG(avg_rating) as avg_rating"))->first();
        return $review->avg_rating;
    }

    /**
     * Get an attribute with its translation
     *
     * @param string $attr
     *
     * @return string
     */
    public function getTranslatedAttribute($attr)
    {
        // Get of current language first
        $value = $this->getAttributeInLanguage($attr, App::getLocale());
        // If it's empty, we'll try to get in the default language
        if (empty($value)) {
            $value = $this->getAttributeInLanguage($attr, Config::get('varaa.default_language'));
        }

        return $value;
    }

    /**
     * Get description as plain text (all html stripped out)
     *
     * @return string
     */
    public function getDescriptionPlainAttribute()
    {
        return strip_tags($this->getDescriptionHtmlAttribute());
    }

    /**
     * Automatically set slug when a name is given
     *
     * @param string $value
     */
    public function getSlugAttribute($value)
    {
        return Str::slug($this->getAttribute('name'));
    }

    public function getHashAttribute()
    {
        return $this->user->hash;
    }

    /**
     * Check if business is using AS
     *
     * @return bool
     */
    public function getIsUsingAsAttribute()
    {
        return \App\Appointment\Models\Employee::where('user_id', $this->user->id)->first();
    }

    /**
     * Check if business is using LC
     *
     * @return bool
     */
    public function getIsUsingLcAttribute()
    {
        return \App\LoyaltyCard\Models\Offer::where('user_id', $this->user->id)->first()
            || \App\LoyaltyCard\Models\Voucher::where('user_id', $this->user->id)->first();
    }

    public function getIconsAttribute()
    {
        $map = [
            'As' => 'fa-calendar',
            'Lc' => 'fa-heart',
            'Fd' => 'fa-bolt'
        ];
        $str = '';

        foreach ($map as $key => $value) {
            if (call_user_func([$this, 'getIsUsing'.$key.'Attribute'])) {
                $str .= ' <i class="fa '.$value.'"></i>';
            }
        }

        return $str;
    }

    /**
     * Get a number of random businesses
     *
     * @param int categoryId
     * @param int quantity
     *
     * @return Illuminate\Support\Collection
     */
    public static function getRandomBusinesses($categoryId, $quantity)
    {
        // $ids = NAT::getRandomBusinesses($categoryId, $quantity);
        // if (!empty($ids)) {
        //     return static::whereIn('user_id', $ids)->get();
        // }

        // it is not efficient to order by RAND() but we have relatively small customers base
        return static::orderBy(\DB::raw('RAND()'))
            ->join('business_category_user', 'business_category_user.user_id', '=', 'businesses.user_id')
            ->where('business_category_user.business_category_id', $categoryId)
            ->where('businesses.name', '!=', '')
            ->where('businesses.is_hidden', 0)
            ->limit($quantity)->get();
    }

     /**
     * Get a number of random businesses has discount
     *
     * @param int quantity
     *
     * @return Illuminate\Support\Collection
     */
    public static function getRamdomBusinesesHasDiscount($quantity)
    {
        return static::orderBy(\DB::raw('RAND()'))
            ->join('as_last_minute_discounts', 'as_last_minute_discounts.user_id', '=', 'businesses.user_id')
            ->join('as_discounts', 'as_discounts.user_id', '=', 'businesses.user_id')
            ->where('businesses.name', '!=', '')
            ->where('businesses.is_hidden', 0)
            ->where(function($query){
                return $query->whereNotNull('as_last_minute_discounts.user_id')
                    ->orwhereNotNull('as_discounts.user_id');
            })->groupBy('businesses.user_id')->limit($quantity)->get();
    }

    public function getRandomMostDiscountedServiceAttribute()
    {
        if (!empty($mostDiscountedService)) {
            return $this->mostDiscountedService;
        }
        
        $services = Service::where('user_id', '=', $this->user_id)
            ->orderBy('price','asc')->where('price', '>', 0)->get();

        $rand = mt_rand(0, 3);

        foreach ($services as $key => $_service) {
            if ($rand == $key){
                $this->mostDiscountedService = $_service;
            }
        }

         return $this->mostDiscountedService;
    }

    /**
     * Update ES index of this business
     *
     * @return void
     */
    public function updateIndex()
    {
        $indexer = new BusinessIndexer($this);
        $indexer->index();
    }
}
