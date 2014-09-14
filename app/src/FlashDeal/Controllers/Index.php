<?php namespace App\FlashDeal\Controllers;

use App, Config, Input;
use App\Core\Controllers\Base;
use App\FlashDeal\Models\FlashDealDate;
use App\FlashDeal\Models\Coupon;

class Index extends Base
{
    protected $viewPath = 'modules.fd.index';

    protected $perPage;

    public function index($tab = null)
    {
        $tab = $tab ?: 'sold-flash-deals';
        $method = camel_case($tab);
        if (!method_exists($this, $method)) {
            App::abort(404);
        }

        $this->perPage = Input::get('perPage', Config::get('view.perPage'));

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
        $all = FlashDealDate::active()->with('flashDeal')->paginate($this->perPage);

        return $this->render('el.flashDeals', [
            'items' => $all
        ]);
    }

    /**
     * Show active coupons in the whole system
     *
     * @return View
     */
    public function activeCoupons()
    {
        $all = Coupon::active()->with('service')->paginate($this->perPage);

        return $this->render('el.coupons', [
            'items' => $all
        ]);
    }

    /**
     * Show expired flash deals
     *
     * @return View
     */
    public function expiredFlashDeals()
    {
        $all = FlashDealDate::expired()
            ->with('flashDeal')
            ->paginate($this->perPage);

        return $this->render('el.flashDeals', [
            'items' => $all
        ]);
    }

    /**
     * Show expired coupons
     *
     * @return View
     */
    public function expiredCoupons()
    {
        $all = Coupon::expired()->with('service')->paginate($this->perPage);

        return $this->render('el.coupons', [
            'items' => $all
        ]);
    }
}
