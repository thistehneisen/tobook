<?php namespace App\Core\Controllers\Ajax;

use Response;

class Base extends \App\Core\Controllers\Base
{
    public function __construct()
    {
        parent::__construct();

        // Attach filter to allow AJAX call only
        $this->beforeFilter('ajax');
    }

    protected function json($data, $status = 200)
    {
        return Response::json($data, $status);
    }

    protected function view($view, $data = [], $mergeData = [])
    {
        return View::make($view, $data, $mergeData);
    }
}
