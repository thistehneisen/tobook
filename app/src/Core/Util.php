<?php namespace App\Core;
use DB;
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
     * pp()
     * Helper function to extend the standard debug function print_r()
     * Note: if you want to debug many variables at the same time, wrap them in an array: printr(array($var1, $var2, $var3));
     *
     * @param mixed $var to be debugged
     * @param bool $hidden is false by default, if $hidden is true then the output is hidden
     * @return null
     **/
    public static function pp($var, $hidden = false) {
        $display = '';
        if ($hidden) $display = 'style="display:none;"';
        echo "<pre {$display}>".var_export($var, true)."</pre>";
    }

    /**
     * lastQuery()
     * Helper function to get the last query
     *
     * @param mixed $var to be debugged
     * @param bool $hidden is false by default, if $hidden is true then the output is hidden
     * @return null
     **/
    public static function lastQuery($hidden = false) {
        $queries = DB::getQueryLog();
        static::pp(last($queries, $hidden));
    }
}
