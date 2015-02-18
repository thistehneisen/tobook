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

        foreach ($definitions as $name => $def) {
            $def['name'] = $name;
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
}
