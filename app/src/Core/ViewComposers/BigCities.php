<?php namespace App\Core\ViewComposers;

use Settings;

class BigCities
{
    public function compose($view)
    {
        $cities = explode("\n", Settings::get('big_cities'));
        $view->with('cities', $cities);
    }
}
