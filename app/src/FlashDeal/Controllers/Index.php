<?php namespace App\FlashDeal\Controllers;

use App, Config, Input;
use App\Core\Controllers\Base;
use App\FlashDeal\Models\FlashDeal;

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

    /**
     * Show active flash deals
     *
     * @return View
     */
    public function activeFlashDeals()
    {
        $perPage = Input::get('perPage', Config::get('view.perPage'));
        $all = FlashDeal::active()->paginate($perPage);

        return $this->render('el.activeFlashDeals', [
            'items' => $all
        ]);
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
