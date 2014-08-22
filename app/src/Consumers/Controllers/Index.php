<?php namespace App\Consumers\Controllers;

use Config;
use App\Core\Controllers\Base;
use App\Consumers\Model as Consumer;

class Index extends Base
{
    protected $viewPath = 'modules.co.';

    /**
     * Show all consumers of the current user
     *
     * @return View
     */
    public function index()
    {
        $consumers = Consumer::paginate(Config::get('view.perPage'));

        return $this->render('index.index', [
            'consumers' => $consumers
        ]);
    }
}
