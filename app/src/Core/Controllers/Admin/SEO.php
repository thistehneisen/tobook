<?php namespace App\Core\Controllers\Admin;

use App;
use Config;
use Input;
use Redirect;

class SEO extends Base
{
    protected $viewPath = 'admin.seo';

    /**
     * Show system settings
     *
     * @return View
     */
    public function index()
    {
        $urls = [
            '/business/register',
            '/auth/forgot-password',
            '/auth/login',
        ];

        return $this->render('index', [
            'urls'      => $urls,
            'languages' => Config::get('varaa.languages'),
            'locale'    => App::getLocale(),
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
