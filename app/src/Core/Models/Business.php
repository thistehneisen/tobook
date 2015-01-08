<?php namespace App\Core\Models;

use Config, Log, NAT, Input, Str, Util, App;
use App\Core\Models\Relations\BusinessBusinessCategory;
use App\Lomake\Fields\HtmlField;
use Exception;
use Illuminate\Support\Collection;
use App\Appointment\Models\Booking;

class Business extends Base
{
    public $fillable = [
        'name',
        'description',
        'size',
        'address',
        'city',
        'postcode',
        'country',
        'phone',
        'lat',
        'lng',
        'note',
        'bank_account',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    public $rulesets = [
        'saving' => [
            'name' => 'required',
            'phone' => 'required',
        ]
    ];

    /**
     * @{@inheritdoc}
     */
    public $isSearchable = true;

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        // whenever updating account, we will try to find geocode of this business
        static::updating(function ($business) {
            $business->updateGeo();

            return true;
        });
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
        $new = $this->full_address;
        $old = $this->getFullAddress(
            $this->getOriginal('address'),
            $this->getOriginal('postcode'),
            $this->getOriginal('city'),
            $this->getOriginal('country')
        );

        if (!empty($new) && $new !== $old) {
            try {
                list($lat, $lng) = Util::geocoder($new);
                $this->attributes['lat'] = $lat;
                $this->attributes['lng'] = $lng;
            } catch (Exception $ex) {
                // Silently fail
            }
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
            'name'             => $input['name'],
            'description'      => HtmlField::filterInput($input, 'description'),
            'size'             => $input['size'],
            'address'          => $input['address'],
            'city'             => $input['city'],
            'postcode'         => $input['postcode'],
            'country'          => $input['country'],
            'phone'            => $input['phone'],
            'note'             => $input['note'],
            'meta_title'       => $input['meta_title'],
            'meta_keywords'    => $input['meta_keywords'],
            'meta_description' => $input['meta_description'],
            'bank_account'     => $input['bank_account'],
        ]);
        $this->user()->associate($user);
        $this->saveOrFail();

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
        return sprintf('%s, %s %s, %s', $address, $postcode, $city, $country);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

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

    public function getImageAttribute()
    {
        $image = $this->user->images()->businessImages()->first();
        if (!empty($image)) {
            return Config::get('varaa.upload_folder').$image->path;
        } else {
            $imageMap = [
                'beauty_hair' => asset_path('core/img/categories/beauty/beauty1.jpg'),
                'hairdresser' => asset_path('core/img/categories/hair/hair1.jpg'),
                'restaurant' => asset_path('core/img/categories/restaurant/res2.jpg'),
                'fine_dining' => asset_path('core/img/categories/restaurant/res8.jpg'),
                'car_wash' => asset_path('core/img/categories/carwash/car1.jpg'),
                'activities' => asset_path('core/img/categories/fitness/fitness1.jpg'),
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
        if (empty($this->attributes['description'])) {
            return '';
        }

        return $this->attributes['description'];
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
        return Str::slug($this->attributes['name']);
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
        $categories = [];
        $keywords = [];

        foreach ($this->businessCategories as $item) {
            $categories[] = $item->nice_original_name;
            $keywords = array_merge($keywords, $item->keywords);
        }

        return [
            // Filter exists only works with null value, so let it be null
            'name'        => $this->name,
            'categories'  => $categories,
            'keywords'    => $keywords,
            'address'     => $this->address ?: '',
            'postcode'    => $this->postcode ?: '',
            'city'        => $this->city ?: '',
            'country'     => $this->country ?: '',
            'phone'       => $this->phone ?: '',
            'description' => $this->description ?: '',
            'location'    => [
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
            'name'        => ['type' => 'string'],
            'categories'  => ['type' => 'string', 'index_name' => 'category'],
            'keywords'    => ['type' => 'string', 'index_name' => 'keyword'],
            'address'     => ['type' => 'string'],
            'postcode'    => ['type' => 'string'],
            'city'        => ['type' => 'string'],
            'country'     => ['type' => 'string'],
            'phone'       => ['type' => 'string'],
            'description' => ['type' => 'string'],
            'location'    => ['type' => 'geo_point'],
        ];
    }

    /**
     * @{@inheritdoc}
     */
    protected function buildSearchQuery($keywords, $fields = null)
    {
        $query = [];
        $query['bool']['should'][]['match']['name']        = $keywords;
        $query['bool']['should'][]['match']['categories']  = $keywords;
        $query['bool']['should'][]['match']['description'] = $keywords;
        $query['bool']['should'][]['match']['keywords']    = $keywords;

        $location = e(Input::get('location'));
        if (!empty($location)) {
            $query['bool']['should'][]['match']['city'] = $location;
            $query['bool']['should'][]['match']['country'] = $location;
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
        $sort = [];

        // Then if there are lat and lng provided, we'll try to use them
        $lat = e(Input::get('lat'));
        $lng = e(Input::get('lng'));
        if ($lat && $lng) {
            $sort[] = [
                '_geo_distance' => [
                    'order'    => 'asc',
                    'unit'     => 'km',
                    'mode'     => 'min',
                    'location' => [
                        'lat' => $lat,
                        'lon' => $lng,
                    ],
                ]
            ];
        }

        return $sort;
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
            $users->push(User::with('business')->find($row['_id']));
        }

        return $users->lists('business');
    }

    /**
     * @{@inheritdoc}
     */
    protected function setCustomSearchParams()
    {
        // We'll show only 5 businesses by default
        $this->customSearchParams = [
            'size' => 5
        ];
    }
}
