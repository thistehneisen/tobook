<?php namespace App\Controllers;

use View, URL, Confide;

class Services extends Base
{
    public function __construct()
    {
        // @todo: Check membership. It's better to have a filter and attach it
        // to this route
    }

    public function cashier()
    {
        $params = ['prefix' => Confide::user()->username];

        // Check if this user has been activated Cashier module
        $params['module'] = (Confide::user()->isCashierActivated())
            ? 'home'
            : 'seed';

        return View::make('services.iframe', [
            'url' => URL::to('cashier/library/index.php?'.http_build_query($params))
        ]);
    }
}
