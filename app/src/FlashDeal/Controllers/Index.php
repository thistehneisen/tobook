<?php namespace App\FlashDeal\Controllers;

use App\Core\Controllers\Base;

class Index extends Base
{
    protected $viewPath = 'modules.fd.index';

    public function index()
    {
        return $this->render('index');
    }
}
