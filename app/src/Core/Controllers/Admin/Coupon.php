<?php namespace App\Core\Controllers\Admin;

class Coupon extends Base
{
	protected $viewPath = 'admin';

    public function index()
    {
        return $this->render('coupon.index', []);
    }

    public function campaigns()
    {
    	return $this->render('coupon.campaigns', []);
    }
}
