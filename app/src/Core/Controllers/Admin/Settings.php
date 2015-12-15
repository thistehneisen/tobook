<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Setting;
use App\Lomake\FieldFactory;
use Config;
use Input;
use Redirect;
use Settings as SettingsModel;

class Settings extends Base
{
    protected $viewPath = 'admin.settings';

    /**
     * Show system settings
     *
     * @return View
     */
    public function index()
    {
        $definitions = Config::get('varaa.settings');
        $controls = [];

        //Don't render booking terms in settings page
        unset($definitions['booking_terms']);

        foreach ($definitions as $name => $def) {
            $def['name'] = $name;
            $def['default'] = isset($def['default'])
                ? $def['default']
                : '';

            // Overwrite with settings in database
            $def['default'] = \Settings::get($name) !== null
                ? \Settings::get($name)
                : $def['default'];

            $controls[] = FieldFactory::create($def);
        }

        return $this->render('index', [
            'controls' => $controls
        ]);
    }

    /**
     * Save system settings
     *
     * @return Redirect
     */
    public function save()
    {
        // Don't use CSRF token
        $input = Input::except('_token');
        // Error message bag
        $errors = null;

        // Save all input as
        foreach ($input as $key => $value) {
            $setting = Setting::findOrNew($key);
            // Need to validate in somewhere else
            if ($key === 'homepage_modal_cookie_expiry_duration') {
                if (!is_numeric($value)){
                    $errors[] = trans('admin.settings.errors.invalid_value_for_expiry_duration');
                    continue;
                }
            }
            $setting->key   = $key;
            $setting->value = $value;
            $setting->save();
        }

        $redirect = Redirect::route('admin.settings');

        if (!empty($errors)) {
            $redirect = $redirect->withErrors($errors);
        }

        return $redirect;
    }

    /**
     * Show form to edit the booking terms
     *
     * @return View
     */
    public function bookingTerms()
    {
        $terms = [];
        $languages = Config::get('varaa.languages');
        $currentLocale = App::getLocale();

        // Get all booking terms in multi-language then create a map for each
        // language
        $text = [];
        $items = SettingsModel::getBookingTerms();
        foreach ($items as $item) {
            $text[$item->lang] = $item->value;
        }

        // Make a map to generate textareas
        foreach ($languages as $lang) {
            $terms[$lang] = (object) [
                'title' => strtoupper($lang),
                'active' => $lang === $currentLocale ? 'active' : '',
                'content' => isset($text[$lang]) ? $text[$lang] : '',
            ];
        }

        return $this->render('booking-terms', [
            'terms' => $terms,
        ]);
    }

    public function saveBookingTerms()
    {
        $input = Input::get('terms');
        SettingsModel::saveBookingTerms($input);

        return Redirect::route('admin.booking.terms');
    }
}
