<?php namespace App\Core\Traits;
use App\Core\Models\Multilanguage;

trait MultilanguageTrait
{
    /**
     * Translate a key based on sepcific context and language
     * @param $key
     * @param $context
     * @param $lang
     * @return string
     */
    public function translate($key, $context, $lang){
        $multilang = Multilanguage::where('lang', '=', $lang)
            ->where('context', '=', $context)
            ->where('key', '=' , $key)
            ->first();

        return (!empty($multilang)) ? $multilang->value : '';
    }
}
