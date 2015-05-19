<?php namespace App\Core\Models;

use App;
use App\Appointment\Models\Booking;
use App\Core\Models\Relations\BusinessBusinessCategory;
use Carbon\Carbon;
use Config;
use Exception;
use Illuminate\Support\Collection;
use Input;
use NAT;
use Settings;
use Str;
use Util;
use Session;

class Business extends Base
{
    public $fillable = [
        'name',
        'phone',
        'description',
        'size',
        'address',
        'city',
        'postcode',
        'country',
        'is_booking_disabled',
        'lat',
        'lng',
        'bank_account',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'is_hidden',
        'note',
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
    ];

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = true;

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
            'city'                => array_get($input, 'city', ''),
            'postcode'            => array_get($input, 'postcode', ''),
            'country'             => array_get($input, 'country', ''),
            'phone'               => array_get($input, 'phone', ''),
            'is_booking_disabled' => array_get($input, 'is_booking_disabled', true),
            'note'                => array_get($input, 'note', ''),
            'is_hidden'           => array_get($input, 'is_hidden', false),
        ]);
        $this->user()->associate($user);
        $this->saveOrFail();

        // Update description
        $this->updateDescription($input['description_html']);
        $this->updateMultiligualAttribute('meta_title', $input['meta_title']);
        $this->updateMultiligualAttribute('meta_keywords', $input['meta_keywords']);
        $this->updateMultiligualAttribute('meta_description', $input['meta_description']);

        // We will remove hidden businesses from indexing
        if ($this->is_hidden) {
            $this->deleteSearchIndex();
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
        return $this->getFullAddress(
            $this->attributes['address'],
            $this->attributes['postcode'],
            $this->attributes['city'],
            $this->attributes['country']
        );
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

    /**
     * Check if business is using FD
     *
     * @return bool
     */
    public function getIsUsingFdAttribute()
    {
        return \App\FlashDeal\Models\FlashDeal::where('user_id', $this->user->id)->first()
            || \App\FlashDeal\Models\Coupon::where('user_id', $this->user->id)->first();
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
            ->join('business_category_user', 'business_category_user.user_id', '=','businesses.user_id')
            ->where('business_category_user.business_category_id', $categoryId)
            ->where('businesses.name', '!=', '')
            ->where('businesses.is_hidden', 0)
            ->limit($quantity)->get();
    }

    //--------------------------------------------------------------------------
    // SEARCH
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getSearchDocumentId()
    {
        return $this->user_id;
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocument()
    {
        // No need to index hidden businesses
        if ($this->is_hidden) {
            return;
        }

        $categories       = [];
        $masterCategories = [];
        $keywords         = [];

        foreach ($this->businessCategories as $item) {
            $categories[] = $item->nice_original_name;
            $keywords = array_merge($keywords, $item->keywords);
        }

        foreach ($this->user->asServices as $asService) {
            if (!empty($asService->masterCategory->id)) {
                $masterCategories[] = $asService->masterCategory->getAllMultilingualAttributes();
            }

            if (!empty($asService->treatmentType->id)) {
                $masterCategories[] = $asService->treatmentType->getAllMultilingualAttributes();
            }
        }

        return [
            // Filter exists only works with null value, so let it be null
            'name'              => $this->name,
            'categories'        => $categories,
            'master_categories' => $masterCategories,
            'keywords'          => $keywords,
            'address'           => $this->address ?: '',
            'postcode'          => $this->postcode ?: '',
            'city'              => $this->city ?: '',
            'country'           => $this->country ?: '',
            'phone'             => $this->phone ?: '',
            'description'       => $this->description ?: '',
            'location'          => [
                'lat' => $this->lat ?: 0,
                'lon' => $this->lng ?: 0
            ]
        ];
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchMapping()
    {
        return [
            'name'              => ['type' => 'string'],
            'categories'        => ['type' => 'string', 'index_name' => 'category'],
            'master_categories' => ['type' => 'string', 'index_name' => 'master_category'],
            'keywords'          => ['type' => 'string', 'index_name' => 'keyword'],
            'address'           => ['type' => 'string'],
            'postcode'          => ['type' => 'string'],
            'city'              => ['type' => 'string'],
            'country'           => ['type' => 'string'],
            'phone'             => ['type' => 'string'],
            'description'       => ['type' => 'string'],
            'location'          => ['type' => 'geo_point'],
        ];
    }

    /**
     * @{@inheritdoc}
     */
    protected function buildSearchQuery($keywords, $fields = null)
    {
        $query = [];
        $query['bool']['should'][]['match']['master_categories'] = $keywords;

        $query['bool']['should'][]['match']['name']              = $keywords;
        $query['bool']['should'][]['match']['categories']        = $keywords;
        $query['bool']['should'][]['match']['description']       = $keywords;
        $query['bool']['should'][]['match']['keywords']          = $keywords;

        $location = Input::get('location');
        if (!empty($location)) {
            $query['bool']['should'][]['match']['city'] = $location;
            $query['bool']['should'][]['match']['country'] = $location;
            $query['bool']['should'][]['match']['address'] = $location;
        }

        return $query;
    }

    /**
     * @{@inheritdoc}
     */
    protected function buildSearchFilter()
    {
        $filters = [];
        $filters['exists'] = ['field' => 'name'];

        return $filters;
    }

    /**
     * @{@inheritdoc}
     */
    protected function buildSearchSortParams()
    {
        list($lat, $lng) = Util::getCoordinates();
        if (Session::has('lat') && Session::has('lng')) {
            $sort = [
                '_geo_distance' => [
                    'unit'     => 'km',
                    'mode'     => 'min',
                    'location' => [
                        'lat' => $lat,
                        'lon' => $lng,
                    ],
                ],
            ];
        } else {
            $sort = [
                '_score' => [
                    'order' => 'desc',
                ],
                '_geo_distance' => [
                    'unit'     => 'km',
                    'mode'     => 'min',
                    'location' => [
                        'lat' => $lat,
                        'lon' => $lng,
                    ],
                ],
            ];
        }

        return $sort;
    }

    /**
     * @{@inheritdoc}
     */
    public function serviceSearch($keyword, array $options = [])
    {
        $this->isSearchByLocation = (!empty($options['isSearchByLocation']))
            ? (bool) $options['isSearchByLocation']
            : false;

        $this->keyword = $keyword;

        //Remove this value to make sure it does not affect the buildSearchParams
        //But still cannot remove this by using unset(), therefore --> []
        $options = [];

        return parent::serviceSearch($keyword, $options);
    }

    public function parentServiceSearch($keyword, array $options = [], $parentModel, $childModel)
    {
        $childModel = App::make(get_class(new static()));
        $childModel->isSearchByLocation = (!empty($options['isSearchByLocation']))
            ? (bool) $options['isSearchByLocation']
            : false;

        $this->keyword = $keyword;

        $options = [];

        return parent::parentServiceSearch($keyword, $options, $parentModel, $childModel);
    }

    public function customTransformSearchResult($result)
    {
        if (empty($result)) {
            return $result;
        }
        $users = new Collection();
        foreach ($result as $row) {
            $business = Business::find($row['_id']);
            if (empty($business)) {
                continue;
            }
            $user = User::with('business')->find($business->user->id);
            if (empty($user) || !empty($user->deleted_at)) {
                continue;
            }
            // Do not add hidden business into the result list
            if ($user->business->is_hidden === true) {
                continue;
            }
            if (isset($row['sort'][1])) {
                $user->distance = $row['sort'][1];
            }
            $users->push($user);
        }
        // Sort to show business that do not disable booking widget
        $users->sortBy(function ($item) {
            return (bool) $item->asOptions->get('disable_booking') === true
                ? 0
                : 1;
        });
        if ($this->isSearchByLocation) {
            // Sort by distance
            $users->sortBy(function ($item) {
                return $item->distance;
            });
        } else {
            // Sort by address matching
            $users->sortByDesc(function ($item) {
                return similar_text($this->keyword, $item->business->full_address);
            });
        }

        return $users->lists('business');
    }

    /**
     * @{@inheritdoc}
     */
    public function transformSearchResult($result)
    {
        if (empty($result)) {
            return $result;
        }
        $users = new Collection();
        foreach ($result as $row) {
            //from Hung: Why we need to get User with business instead of get user from business?
            //e.g: $business = Business::find($id) --> $business->user;
            $user = User::with('business')->find($row['_id']);

            if (!empty($user->deleted_at)) {
                continue;
            }

            if (isset($row['sort'][1])) {
                $user->distance = $row['sort'][1];
            }
            $users->push($user);
        }

        // if ($this->isSearchByLocation) {
        //     // Sort by distance
        //     $users->sortBy(function ($item) {
        //         return $item->distance;
        //     });
        // } else {
        //     // Sort by address matching
        //     $users->sortByDesc(function ($item) {
        //         return similar_text($this->keyword, $item->business->full_address);
        //     });
        // }
        return $users->lists('business');
    }

    /**
     * @{@inheritdoc}
     */
    protected function setCustomSearchParams()
    {
        // We'll show only 5 businesses by default
        $this->customSearchParams = [
            'size' => 15
        ];
    }

    public function updateSearchIndex($searchIndexName = null)
    {
        // If this model is not searchable, return as soon as possible
        if ($this->isSearchable === false) {
            return;
        }

        $params = [];
        $params['index'] = (!empty($searchIndexName)) ? $searchIndexName : $this->getSearchIndexName();
        $params['type']  = (!empty($searchIndexName)) ? str_singular($searchIndexName) : $this->getSearchIndexType();
        $params['id']    = $this->getSearchDocumentId();
        $params['body']  = $this->getSearchDocument();
        $provider = App::make('App\Search\ProviderInterface');

        return $provider->index($params);
    }
}
