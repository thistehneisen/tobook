<?php namespace App\Core\ViewComposers;

use Settings;

class BigCities
{
    public function compose($view)
    {
        $cities = array_filter(explode("\n", Settings::get('big_cities')));
        $view->with('cities', $cities);

        $districts = array_filter(explode("\n", trim(Settings::get('districts'))));
        $view->with('districts', $districts);
    }
}
