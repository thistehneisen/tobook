<?php namespace App\Core;
use DB, Str, App;
use Carbon\Carbon;
/**
 * Providing a set of utility functions
 */
class Util
{
    /**
     * Genereate a random 12-character length string likes AZ0123456789
     * Legacy from old source code
     * @return string
     */
    public static function uuid()
    {
        return chr(rand(65,90)) . chr(rand(65,90)) . time();
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
        if(App::getLocale() !== 'en')
            return trans('common.'. Str::lower($string));
        return $string;
    }

    public static function getHtmlListError(\Watson\Validating\ValidationException $ex)
    {
        return '<ul>' . implode('', $ex->getErrors()->all('<li>:message</li>')) .'</ul>';
    }
}
