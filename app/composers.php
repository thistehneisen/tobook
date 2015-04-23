<?php
/**
 * Inject business categories to all views
 */
View::composer('layouts.default', function ($view) {
    $categories = \App\Appointment\Models\MasterCategory::getAll();
    $view->with('masterCategories', $categories);
    // We will use those coordinates to see if we should ask for current
    // location of user
    // @see: /resources/varaa/core/scripts/home.coffee
    $view->with('lat', Session::get('lat'));
    $view->with('lng', Session::get('lng'));
});

/**
 * Replace META tags with localized texts set in Admin page
 */
View::composer('el.multimeta', function ($view) {
    $uri = App::environment() === 'tobook'
        ? str_replace('site/', '', Request::path())
        : Request::path();

    $result = \App\Core\Models\Multilanguage::where('context', $uri)
        ->where('lang', App::getLocale())
        ->get();

    $view->with('meta', $result);
});
