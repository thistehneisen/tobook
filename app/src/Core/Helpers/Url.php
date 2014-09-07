<?php namespace App\Core\Helpers;
use Request, Config, URL as UrlGenerator;

class Url
{
    /**
     * Get the current URL and localize it
     *
     * @return string
     */
    public static function localizedCurrentUrl($locale)
    {
        $languages = Config::get('varaa.languages');
        if (!in_array($locale, $languages)) {
            throw new \InvalidArgumentException('Upsupported locale: '.$locale);
        }
        return UrlGenerator::to('/'.$locale.Request::getRequestUri());;
    }
}
