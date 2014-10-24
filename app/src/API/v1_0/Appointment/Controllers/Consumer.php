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

    /**
     * Store a newly created consumer.
     *
     * @return Response
     */
    public function store()
    {
        $consumer = new \App\Appointment\Models\Consumer(Input::all());
        $consumer->save();

        if ($consumer->id) {
            $consumer->users()->attach($this->user);

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
        $consumer = \App\Appointment\Models\Consumer::ofCurrentUser()->findOrFail($id);

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
        $consumer = \App\Appointment\Models\Consumer::ofCurrentUser()->findOrFail($id);

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
        $consumer = \App\Appointment\Models\Consumer::ofCurrentUser()->findOrFail($id);

        $consumer->delete();

        return Response::json([
            'error' => false,
            'message' => 'Consumer has been deleted.',
        ], 200);
    }
}
