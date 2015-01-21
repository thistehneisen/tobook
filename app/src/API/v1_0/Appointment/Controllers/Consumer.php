<?php namespace App\API\v1_0\Appointment\Controllers;

use Input, Response;
use \App\Consumers\Models\Consumer as CoreConsumer;

class Consumer extends Base
{
    /**
     * Display consumers of current business owner.
     *
     * @return Response
     */
    public function index()
    {
        $consumers = CoreConsumer::ofCurrentUser();
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

    /**
     * Store a newly created consumer.
     *
     * @return Response
     */
    public function store()
    {
        $consumer = CoreConsumer::make(Input::all(), $this->user);

        if ($consumer->id) {
            return Response::json([
                'error' => false,
                'data' => $this->_prepareConsumerData($consumer),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => $consumer->getErrors()->first(),
            ], 400);
        }
    }


    /**
     * Display the specified consumer.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $consumer = CoreConsumer::ofCurrentUser()->findOrFail($id);

        return Response::json([
            'error' => false,
            'data' => $this->_prepareConsumerData($consumer),
        ], 200);
    }


    /**
     * Update the specified consumer.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $consumer = CoreConsumer::ofCurrentUser()->findOrFail($id);

        $consumer->fill(Input::all());

        if ($consumer->save()) {
            return Response::json([
                'error' => false,
                'data' => $this->_prepareConsumerData($consumer),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => $consumer->getErrors()->first(),
            ], 400);
        }
    }

    /**
     * Delete the specified consumer.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $consumer = CoreConsumer::ofCurrentUser()->findOrFail($id);

        $consumer->delete();

        return Response::json([
            'error' => false,
            'message' => 'Consumer has been deleted.',
        ], 200);
    }
}
