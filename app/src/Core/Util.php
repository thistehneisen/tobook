<?php namespace App\Core;

use App;
use Cache;
use Carbon\Carbon;
use Config;
use Exception;
use Geocoder;
use Guzzle\Http\Client;
use Imagine;
use Input;
use InvalidArgumentException;
use Log;
use Request;
use Session;
use Settings;
use Str;

/**
 * Providing a set of utility functions
 */
class Util
{
    /**
     * Generate a random 16-character string for booking UUID
     * NOTE: there should be module specific method to wrap around this method
     * to guarantee each uuid generate is unique
     * Check example in \App\Appointment\Models\Booking::uuid()
     *
     * @return string
     */
    public static function uuid($length = 16)
    {
        // use quickRandom for faster performance
        return Str::quickRandom($length);
    }

    /**
     * Return 3 letters present day of week based on Carbon constant dayOfWeek
     *
     * @return string
     */
    public static function getDayOfWeekText($weekday)
    {
        $dayOfWeek = '';
         switch ($weekday) {
            case Carbon::MONDAY:
                $dayOfWeek = 'mon';
                break;
            case Carbon::TUESDAY:
                $dayOfWeek = 'tue';
                break;
            case Carbon::WEDNESDAY:
                $dayOfWeek = 'wed';
                break;
            case Carbon::THURSDAY:
                $dayOfWeek = 'thu';
                break;
            case Carbon::FRIDAY:
                $dayOfWeek = 'fri';
                break;
            case Carbon::SATURDAY:
                $dayOfWeek = 'sat';
                break;
            case Carbon::SUNDAY:
                $dayOfWeek = 'sun';
                break;
            default:
                $dayOfWeek = self::getDayOfWeekText(Carbon::now()->dayOfWeek);
                break;
        }

        return $dayOfWeek;
    }

    /**
     * Translate a date from English to default language
     *
     * @return string
     */
    public static function td($string)
    {
        if (App::getLocale() !== 'en') {
            return trans('common.'.Str::lower($string));
        }

        return $string;
    }

    /**
     * Get validation errors as a HTML list
     *
     * @return string
     */
    public static function getHtmlListError(\Watson\Validating\ValidationException $ex)
    {
        return '<ul>'.implode('', $ex->getErrors()->all('<li>:message</li>')).'</ul>';
    }

    /**
     * Get or create thumbnail for given image
     *
     * @return string
     */
    public static function thumbnail($imagePath, $width, $height, $returnUrl = true, $hardCrop = false)
    {
        if (!file_exists($imagePath)) {
            // show the missing file to debug
            return $returnUrl ? asset($imagePath) : $imagePath;
        }

        $imageFile = basename($imagePath);
        $image = explode('.', $imageFile);
        $imageName = $image[0];
        $ext = $image[1];
        $thumbPath = sprintf('%s/thumbnails/%s__%sx%s.%s',
            Config::get('varaa.upload_folder'),
            $imageName, $width, $height, $ext
        );

        // regenerate the thumbnail if needed
        if (!file_exists($thumbPath)) {
            $imagine = new Imagine\Gd\Imagine();
            $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
            if ($hardCrop) {
                $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
            }
            $size = new Imagine\Image\Box($width, $height);

            try {
                $imagine->open($imagePath)
                    ->thumbnail($size, $mode)
                    ->save($thumbPath);
            } catch (\Exception $ex) {}
        }

        return $returnUrl ? asset($thumbPath) : $thumbPath;
    }

    /**
     * Decode lat, lng coords of the given location
     *
     * @param string $location
     *
     * @return array
     */
    public static function geocoder($location)
    {

        $location = trim($location);
        //Remove all country part before append default country
        $location = self::cleanupCountryName($location);

        if (empty($location)) {
            throw new InvalidArgumentException('A location must be provided to be geo-located');
        }

        $location = sprintf("%s, %s", $location, self::getCountryOfInstance());

        // I don't want to have whitespaces and/or other special characters as
        // cache key
        $key = 'geocoder.'.md5($location);
        $pair = Cache::get($key);
        if (!empty($pair)) {
            return $pair;
        }

        try {
            $geocode = Geocoder::geocode($location);
            $pair = [
                $geocode->getLatitude(),
                $geocode->getLongitude()
            ];

            // Save into cache
            Cache::forever($key, $pair);

            return $pair;
        } catch (\Exception $ex) {
            Log::warning('Cannot geocode location: '.$ex->getMessage());
            // We don't want to handle this, just logging and throw it away
            throw $ex;
        }
    }

    public static function cleanupCountryName($location)
    {
        $countries = [
            'Latvia',
            'Latvija',
            'Латвия',
            'Finland',
            'Suomi',
            'Russia',
            'Россия',
            'Sweden',
            'Sverige'
        ];

        $location = mb_strtolower($location);
        foreach ($countries as $country) {
            $name = mb_strtolower($country);
            $location = mb_ereg_replace($name, '', $location);
        }

        return $location;
    }

    /**
     * Return the operating country of the environment
     * for more geocoder easier to find a location
     *
     * @return string
     */
    public static function getCountryOfInstance()
    {
        $data = [
            'prod'         => 'Finland',
            'stag'         => 'Finland',
            'testing'      => 'Finland',
            'tobook'       => 'Latvia',
            'clearbooking' => 'Sweden'
        ];
        $language = (!empty($data[App::environment()]))
            ? $data[App::environment()]
            : 'Finland';
        return $language;
    }

    /**
     * Get current coordinates
     *
     * @return array: [$lat, $lng]
     */
    public static function getCoordinates()
    {
        $lat      = Input::get('lat');
        $lng      = Input::get('lng');
        $location = Input::get('location');
        $currentLocation = trans('home.search.current_location');

        if (!empty($location) && $location !== $currentLocation) {
            // try {
                list($lat, $lng) = self::geocoder($location);
            // } catch (Exception $ex) {
            //     Log::error('Cannot geodecode '.$location);
            // }
        } elseif ($lat && $lng) {
            Session::set('lat', $lat);
            Session::set('lng', $lng);
        } elseif (Session::has('lat') && Session::has('lng')) {
            $lat = Session::get('lat');
            $lng = Session::get('lng');
        } else {
            // Try to get location based on IP
            $data = static::decodeIp();
            if ($data && $data->city) {
                Session::set('lat', $data->city->lat);
                Session::set('lng', $data->city->lon);

                $lat = $data->city->lat;
                $lng = $data->city->lon;
            } else {
                // Final blast, use default coords of the system
                // Maybe Helsinki, Stockholm, Tallinn, etc.
                list($lat, $lng) = Config::get('varaa.default_coords');
            }
        }

        return [$lat, $lng];
    }

    /**
     * Check if 2 phone numbers are similar
     *
     * @param  string  $a
     * @param  string  $b
     * @return boolean
     */
    public static function isSimilarPhoneNumber($a, $b)
    {
        // First we'll trim and remove non-numeric characters in both arguments
        $a = preg_replace('/[^0-9]+/', '', $a);
        $b = preg_replace('/[^0-9]+/', '', $b);

        // Then test if the longer phone number contain the shorter phone number
        $shorter = $a;
        $longer  = $b;

        if (strlen($a) > strlen($b)) {
            $shorter = $b;
            $longer = $a;
        }

        return str_contains($longer, $shorter);
    }

    /**
     * Check if the current user is in stealth mode
     *
     * @return bool
     */
    public static function inStealthMode()
    {
        return \Entrust::hasRole(App\Core\Models\Role::ADMIN)
         || \Session::has('stealthMode');
    }

    /**
     * Get the current IP and collect relevant data using Sypexgeo.net
     *
     * @see  https://sypexgeo.net/ru/api/ Sypexgeo API
     *
     * @return stdClass
     */
    public static function decodeIp()
    {
        $ip = Request::getClientIp();
        $key = 'ip:'.$ip;
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $client = new Client();
        $req = $client->get('https://api.sypexgeo.net/json/'.$ip);
        $res = $client->send($req);
        if ($res->getStatusCode() === 200) {
            $json = json_decode($res->getBody());
            Cache::forever($key, $json);

            return $json;
        }
    }

    /**
     * Get name of current location based on user input, IP decoding, or default
     * system location
     *
     * @return string
     */
    public static function getCurrentLocation()
    {
        if (Input::has('location')) {
            return Input::get('location');
        }

        $ipData = static::decodeIp();
        if ($ipData && $ipData->city !== null) {
            $field = App::getLocale() === 'ru' ? 'name_ru' : 'name_en';
            $location = $ipData->city->$field;

            if ($ipData->country !== null) {
                return $location.', '.$ipData->country->$field;
            }

            return $location;
        }

        // If cannot get location from those two criteria, return the default
        // location
        return Settings::get('default_location');
    }

    public static function getMonthsSelection(Carbon $current)
    {
        $labels = [
            trans('common.january'),
            trans('common.february'),
            trans('common.march'),
            trans('common.april'),
            trans('common.may'),
            trans('common.june'),
            trans('common.july'),
            trans('common.august'),
            trans('common.september'),
            trans('common.october'),
            trans('common.november'),
            trans('common.december'),
        ];

        $months = [];

        for ($i=0; $i<12; $i++) {
            $month = $i + 1;
            $months[Carbon::createFromFormat('Y-m', $current->year . '-' . $month)->format('Y-m')] = $labels[$i];
        }

        return $months;
    }

    /**
     * Convert real number to percentage format
     * e.g: 0.3 --> 30%
     */
    public static function formatPercentage($percent)
    {
        return ($percent * 100);
    }

    public static function formatMoney($amount)
    {
        return number_format($amount, 2);
    }
}
