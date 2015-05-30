<?php namespace App\Core\Controllers\Admin;

use App;
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
}
