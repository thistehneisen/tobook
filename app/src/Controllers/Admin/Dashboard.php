<?php namespace App\Controllers\Admin;

class Dashboard extends Base
{
    public function index()
    {
        return $this->render('dashboard.index', []);
    }
}
