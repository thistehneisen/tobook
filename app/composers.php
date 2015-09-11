<?php
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

View::composer('layouts.default', 'App\Core\ViewComposers\DefaultLayout');
View::composer('layouts.default', 'App\Core\ViewComposers\BigCities');
View::composer('front.el.search.form', 'App\Core\ViewComposers\BigCities');
View::composer('front.el.search.default', 'App\Core\ViewComposers\BigCities');
View::composer('front.el.search.default', function ($view) {
    $request = Request::instance();
    $name = Route::currentRouteName();

    $action = route('search');
    if ($name === 'business.master_category' || $name === 'business.treatment') {
        $action = $request->fullUrl();
    }

    $view->with('action', $action);
});
