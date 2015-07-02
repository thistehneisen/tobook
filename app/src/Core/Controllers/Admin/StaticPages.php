<?php namespace App\Core\Controllers\Admin;

use App;
use App\Core\Models\Setting;
use Input;
use Redirect;
use Settings;
use Config;

class StaticPages extends Base
{
    protected $viewPath = 'admin.pages';

    public function index()
    {
        $currentLocale = App::getLocale();
        $languages = [];
        foreach (Config::get('varaa.languages') as $lang) {
            $languages[$lang] = [
                'title' => strtoupper($lang),
                'active' => $lang === $currentLocale ? 'active' : '',
                'content' => '',
            ];
        }
        $pages = [
            'terms_conditions' => $languages,
            'privacy_cookies' => $languages,
        ];

        foreach (['terms_conditions', 'privacy_cookies'] as $key) {
            $settings = Settings::getByLanguage($key);
            foreach ($settings as $setting) {
                $pages[$key][$setting->lang]['content'] = $setting->value;
            }
        }

        return $this->render('index', [
            'pages' => $pages,
        ]);
    }

    public function save()
    {
        Settings::saveMultilingual(Input::get('name'), Input::get('content'));

        return Redirect::route('admin.pages');
    }
}
