<?php namespace App\Core;

use DB, Str, App, Config, Geocoder, Cache, Log, Imagine, Input, Session, Carbon\Carbon;
use Exception, InvalidArgumentException;

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

        if (empty($location)) {
            throw new InvalidArgumentException('A location must be provided to be geo-located');
        }

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

    /**
     * Get current coordinates
     *
     * @return array: [$lat, $lng]
     */
    public static function getCoordinates()
    {
        $lat = Input::get('lat');
        $lng = Input::get('lng');

        // If there is lat and lng values, we'll store in Session so that we
        // don't need to as again
        if ($lat && $lng) {
            Session::set('lat', $lat);
            Session::set('lng', $lng);
        } else {
            // Helsinki
            $lat = '60.1733244';
            $lng = '24.9410248';
        }

        $location = Input::get('location');
        try {
            list($lat, $lng) = self::geocoder($location);
        } catch (\Exception $ex) {
            // Silently failed
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
}
