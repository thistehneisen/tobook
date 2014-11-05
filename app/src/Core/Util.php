<?php namespace App\Core;

use DB, Str, App, Config;
use Carbon\Carbon;
use Imagine;

/**
 * Providing a set of utility functions
 */
class Util
{
    /**
     * Generate a random 36-character string for Booking UUID
     * @return string
     */
    public static function uuid()
    {
        // Legacy from old source code, 12 char (not so random)
        //return chr(rand(65,90)) . chr(rand(65,90)) . time();

        return str_random(26).time();
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
}
