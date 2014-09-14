<?php
/**
 * Inject business categories to all views
 */
View::composer('layouts.default', function($view) {
    $categories = \App\Core\Models\BusinessCategory::getAll();
    $view->with('businessCategories', $categories);
});
