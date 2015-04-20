<?php
/**
 * Inject business categories to all views
 */
View::composer('layouts.default', function ($view) {
    $categories = \App\Appointment\Models\MasterCategory::getAll();
    $view->with('masterCategories', $categories);
});

/**
 * Replace META tags with localized texts set in Admin page
 */
View::composer('el.multimeta', function ($view) {
    $uri = Request::path();
    $result = \App\Core\Models\Multilanguage::where('context', $uri)
        ->where('lang', App::getLocale())
        ->get();

    $view->with('meta', $result);
});
