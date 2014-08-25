<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use Auth;
use App\LoyaltyCard\Models\Consumer as Model;
use App\Core\Controllers\Base as Base;

class Consumer extends Base
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        // get all the consumers
        $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->where('user_id', Auth::user()->id)
                        ->get();

        return Response::json([
            'error' => false,
            'consumers' => $consumers->toArray(),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email',
            'phone'         => 'required|numeric',
            'address'       => 'required',
            'postcode'      => 'required|numeric',
            'city'          => 'required',
            'country'       => 'required',
        ];

        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => 'Invalid data',
            ], 401);
        } else {
            try {
                // Create core consumer first
                $core = Model::createCore();
                $core->first_name = Request::get('first_name');
                $core->last_name  = Request::get('last_name');
                $core->user_id    = Auth::user()->id;
                $core->email      = Request::get('email');
                $core->phone      = Request::get('phone');
                $core->address    = Request::get('address');
                $core->postcode   = Request::get('postcode');
                $core->city       = Request::get('city');
                $core->country    = Request::get('country');
                $core->save();

                // Then create a LC consumer
                $consumer = new Model;
                $consumer->total_points = 0;
                $consumer->total_stamps = '';
                $consumer->consumer_id = $core->id;
                $consumer->consumer()->associate($core);
                $consumer->save();
            } catch (Exception $ex) {

            }

            return Response::json([
                'error' => false,
                'message' => 'Consumer created',
            ], 201);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $consumer = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                            ->where('user_id', Auth::user()->id)
                            ->where('consumers.id', $id)
                            ->take(1)
                            ->get();

        return Response::json([
            'error' => false,
            'consumer' => $consumer->toArray(),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $consumer = Model::find($id);

        foreach (['first_name', 'last_name', 'email', 'phone', 'address', 'postcode', 'city', 'country'] as $key) {
            if (Request::get($key)) {
                $consumer->consumer->$key = Request::get($key);
            }
        }

        $consumer->consumer->save();

        return Response::json([
            'error' => false,
            'message' => 'Consumer updated',
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $consumer = Model::find($id);
            $consumer->delete();
            $consumer->consumer->delete();
        } catch (Exception $ex) {

        }

        return Response::json([
            'error' => false,
            'message' => 'Consumer deleted',
        ], 204);
    }
}
