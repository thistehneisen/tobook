<?php namespace App\Core\Controllers\Admin;

use App\Core\Models\Setting;
use App\Lomake\FieldFactory;
use Config;
use Input;
use Redirect;

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

        // Save all input as
        foreach ($input as $key => $value) {
            $setting = Setting::findOrNew($key);
            $setting->key   = $key;
            $setting->value = $value;
            $setting->save();
        }

        return Redirect::route('admin.settings');
    }

    public function bookingTerms()
    {
        $definitions = Config::get('varaa.settings');
        $controls = [];

        $name = 'booking_terms';

        $definitions[$name]['name'] = $name;
        $definitions[$name]['default'] = isset($definitions[$name]['default'])
            ? $definitions[$name]['default']
            : '';

        // Overwrite with settings in database
        $definitions[$name]['default'] = \Settings::get($name) !== null
            ? \Settings::get($name)
            : $definitions[$name]['default'];

        $controls[] = FieldFactory::create($definitions[$name]);

        return $this->render('booking-terms', [
            'controls' => $controls
        ]);
    }

    public function saveBookingTerms()
    {
         // Don't use CSRF token
        $input = Input::except('_token');

        // Save all input as
        foreach ($input as $key => $value) {
            $setting = Setting::findOrNew($key);
            $setting->key   = $key;
            $setting->value = $value;
            $setting->save();
        }

        return Redirect::route('admin.booking.terms');
    }
}
