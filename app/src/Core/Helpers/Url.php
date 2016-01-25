<?php namespace App\Core\Helpers;
use Request, Config, URL as UrlGenerator;

class Url
{
    /**
     * Get the current URL and localize it
     *
     * @return string
     */
    public static function localizeCurrentUrl($locale)
    {
        $languages = Config::get('varaa.languages');
        if (!in_array($locale, $languages)) {
            throw new \InvalidArgumentException('Upsupported locale: '.$locale);
        }
        
        $url = UrlGenerator::to('/'.$locale.Request::getRequestUri());

        if (is_tobook() && in_array($_SERVER['REMOTE_ADDR'], 
            ['62.63.137.2','62.63.137.4', '62.63.137.6', '62.63.137.205'])) {
            $url = str_replace($_SERVER['HTTP_HOST'], 'www.delfi.lv/tobook', $url);
        }
        
        return $url;
    }
}
