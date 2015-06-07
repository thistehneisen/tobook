<?php namespace App\Core\ViewComposers;

use Settings;

class BigCities
{
    public function compose($view)
    {
        $cities = explode("\n", Settings::get('big_cities'));
        $view->with('cities', array_filter($cities));

        $districts = explode("\n", trim(Settings::get('districts')));
        $view->with('districts', array_filter($districts));
    }
}
