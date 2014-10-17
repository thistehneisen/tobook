<?php namespace App\API\v1_0\Appointment\Controllers;

use Input, Response;

class Consumer extends Base
{
    /**
     * Display consumers of current business owner.
     *
     * @return Response
     */
    public function index()
    {
        $consumers = \App\Appointment\Models\Consumer::ofCurrentUser();
        $perPage = max(1, intval(Input::get('per_page', 15)));
        $consumers = $consumers->paginate($perPage);

        $consumersData = [];
        foreach ($consumers as $consumer) {
            $consumersData[] = $this->_prepareConsumerData($consumer);
        }

        return Response::json([
            'error' => false,
            'data' => $consumersData,
            'pagination' => $this->_preparePagination($consumers),
        ]);
    }
}
