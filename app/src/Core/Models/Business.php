<?php namespace App\Core\Models;

use Config;
use App\Core\Models\Relations\BusinessBusinessCategory;

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
    ];

    public $rulesets = [
        'saving' => [
            'name' => 'required',
            'phone' => 'required',
        ]
    ];

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
        $instance = new BusinessCategory;
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
                $geocode = \Geocoder::geocode($new);
                $this->attributes['lat'] = $geocode->getLatitude();
                $this->attributes['lng'] = $geocode->getLongitude();
            } catch (\Exception $ex) {
                // Silently fail
                \Log::error($ex->getMessage(), ['context' => 'Update user profile']);
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


    public function updateSearchIndex()
    {
        return \App\Search\Servant::getInstance()->upsertIndexForBusiness($this);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

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
                'Beauty &amp; Hair' => 'assets/img/categories/beauty/beauty1.jpg',
                'Hairdresser' => 'assets/img/categories/hair/hair1.jpg',
                'Restaurant' => 'assets/img/categories/restaurant/res2.jpg',
                'Fine Dining' => 'assets/img/categories/restaurant/res8.jpg',
                'Car Wash' => 'assets/img/categories/carwash/car1.jpg',
                'Activities' => 'assets/img/categories/fitness/fitness1.jpg',
            ];

            foreach ($this->businessCategories as $cat) {
                return isset($imageMap[$cat->name]) ? $imageMap[$cat->name] : 'assets/img/categories/fitness/fitness1.jpg';
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

        static::saved(function ($user) {
            $user->updateSearchIndex();
        });
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
            $queryString = '%' . $q . '%';
            $query = $query->whereHas(
                'businessCategories',
                function ($query) use ($queryString) {
                    return $query->where('name', 'LIKE', $queryString)
                        ->orWhere('keywords', 'LIKE', $queryString);
                }
            )->orWhere('name', 'LIKE', $queryString);
        }

        if (!empty($location)) {
            $query = $query->where('city', 'LIKE', '%' . $location . '%');
        }

        $businesses = $query
            ->where('name', '!=', '')
            ->paginate(Config::get('view.perPage'));

        return $businesses;
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
        // it is not efficient to order by RAND() but we have relatively small customers base
        return static::orderBy(\DB::raw('RAND()'))
            ->join('business_category_user', 'business_category_user.user_id', '=','businesses.user_id')
            ->where('business_category_user.business_category_id', $categoryId)
            ->where('businesses.name', '!=', '')
            ->limit($quantity)->get();
    }
}
