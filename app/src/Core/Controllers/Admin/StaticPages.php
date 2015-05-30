<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Setting;
use Input;
use Redirect;
use Settings;

class StaticPages extends Base
{
    protected $viewPath = 'admin.pages';

    public function index()
    {
        $pages = [
            'terms_conditions' => Settings::get('terms_conditions'),
            'privacy_cookies' => Settings::get('privacy_cookies'),
        ];

        return $this->render('index', [
            'pages' => $pages
        ]);
    }

    public function save()
    {
        $setting = Setting::findOrNew(Input::get('name'));
        $setting->key   = Input::get('name');
        $setting->value = Input::get('content');
        $setting->save();

        return Redirect::route('admin.pages');
    }
}
