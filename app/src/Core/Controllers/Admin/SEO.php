<?php namespace App\Core\Controllers\Admin;

use App;
use Config;
use Input;
use Redirect;
use App\Core\Models\Multilanguage;

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
            'business/register',
            'auth/forgot-password',
            'auth/login',
        ];

        $values = [];
        foreach ($urls as $url) {
            $result = Multilanguage::where('context', $url)->get();
            foreach ($result as $item) {
                $values[$url][$item->lang][$item->key] = $item->value;
            }
        }

        return $this->render('index', [
            'urls'      => $urls,
            'languages' => Config::get('varaa.languages'),
            'locale'    => App::getLocale(),
            'values'    => $values,
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

        foreach ($input as $context => $values) {
            foreach ($values as $lang => $keys) {
                foreach ($keys as $key => $value) {
                    Multilanguage::saveValue(
                        '',
                        $context,
                        $lang,
                        $key,
                        $value
                    );
                }
            }
        }

        return Redirect::route('admin.seo');
    }
}
