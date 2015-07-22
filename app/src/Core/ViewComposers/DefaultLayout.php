<?php namespace App\Core\ViewComposers;

use App;
use App\Appointment\Models\MasterCategory;
use Session;
use View;

class DefaultLayout
{
    public function compose($view)
    {
        $env = App::environment();
        $footerView = View::exists('layouts.footers.'.$env)
            ? 'layouts.footers.'.$env
            : 'layouts.footers.default';

        if(!empty($view['routeName']) && ($view['routeName'] === 'as.index' || $view['routeName'] === 'as.employee')
            && App::environment() === 'tobook') {
            $footerView = 'layouts.footers.blank';
        }

        $categories = MasterCategory::getAll();
        $view->with('masterCategories', $categories);
        // We will use those coordinates to see if we should ask for current
        // location of user
        // @see: /resources/varaa/core/scripts/home.coffee
        $view->with('lat', Session::get('lat'));
        $view->with('lng', Session::get('lng'));
        $view->with('env', $env);
        $view->with('footerView', $footerView);
    }
}
