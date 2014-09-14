<?php namespace App\FlashDeal\Controllers;

use App;
use App\Core\Controllers\Base;

class Index extends Base
{
    protected $viewPath = 'modules.fd.index';

    public function index($tab = null)
    {
        $tab = $tab ?: 'sold-flash-deals';
        $method = camel_case($tab);
        if (!method_exists($this, $method)) {
            App::abort(404);
        }

        $content = $this->$method();
        return $this->render('index', [
            'tab'     => $tab,
            'content' => $content
        ]);
    }

    public function soldFlashDeals()
    {
        return 'hehe';
    }

    public function soldCoupons()
    {
        return 'hehe';
    }

    public function activeFlashDeals()
    {
        return 'hehe';
    }

    public function activeCoupons()
    {
        return 'hehe';
    }

    public function expired()
    {
        return 'hehe';
    }

}
