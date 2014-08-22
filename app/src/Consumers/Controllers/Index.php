<?php namespace App\Consumers\Controllers;

use Config;
use Consumer;
use App\Core\Controllers\Base;

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
        $consumers = Consumer::ofCurrentUser()
            ->paginate(Config::get('view.perPage'));

        return $this->render('index.index', [
            'consumers' => $consumers
        ]);
    }

    /**
     * Edit a consumer
     *
     * @param int $id Consumer's ID
     *
     * @return View
     */
    public function edit($id)
    {
        $consumer = Consumer::find($id);

        return $this->render('index.edit', [
            'consumer' => $consumer
        ]);
    }
}
