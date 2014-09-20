<?php namespace App\Core\Controllers;

use View, Validator, Input, Redirect, Config, Session, URL;
use User;
use Confide;

class Dashboard extends Base
{
    protected $viewPath = 'dashboard';

    public function index()
    {
        if (Confide::user()->is_consumer) {
            return $this->render('consumer');
        }

        // Get all modules availables for this user
        $modules = Confide::user()->modules;

        return $this->render('index', [
            'user'    => Confide::user(),
            'modules' => $modules,
        ]);
    }
}
