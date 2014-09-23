<?php namespace App\Core\Controllers\Ajax;

use Config;

class JsLocale extends Base
{
    /**
     * Get all JS locale texts
     *
     * @return JSON
     */
    public function getJsLocale()
    {
        //TODO: implement caching
        $keys = Config::get('jslocale.keys');
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = trans($key);
        }
        return $this->json($data);
    }
}
