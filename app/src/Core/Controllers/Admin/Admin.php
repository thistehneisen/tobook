<?php namespace App\Core\Controllers\Admin;

class Admin extends Base
{
    protected $viewPath = 'admin.admin';

    /**
     * Show the form to create new admin
     *
     * @return View
     */
    public function create()
    {
        return $this->render('create');
    }
}
