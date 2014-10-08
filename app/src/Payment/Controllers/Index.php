<?php namespace App\Payment\Controllers;

class Index extends Base
{
    /**
     * Show page for user to make payment
     *
     * @return View
     */
    public function index()
    {
        return $this->render('index');
    }
}
