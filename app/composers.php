<?php
/**
 * Inject business categories to all views
 */
View::composer('layouts.default', function ($view) {
    $categories = \App\Appointment\Models\MasterCategory::getAll();
    $view->with('masterCategories', $categories);
});
