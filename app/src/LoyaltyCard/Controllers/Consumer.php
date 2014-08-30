<?php namespace App\LoyaltyCard\Controllers;

use Input, Session, Redirect, View, Validator;
use Confide;
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
        $consumers = Model::paginate(10);

        // load the view and pass the consumers
        return View::make('modules.lc.consumers.index')
            ->with('consumers', $consumers);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modules.lc.consumers.create');
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

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create core consumer first
            $core = Model::createCore();
            $core->first_name = Input::get('first_name');
            $core->last_name  = Input::get('last_name');
            $core->user_id    = Confide::user()->id;
            $core->email      = Input::get('email');
            $core->phone      = Input::get('phone');
            $core->address    = Input::get('address');
            $core->postcode   = Input::get('postcode');
            $core->city       = Input::get('city');
            $core->country    = Input::get('country');
            $core->save();

            // Then create a LC consumer
            $consumer = new Model;
            //$consumer->score = Input::get('score', 0);
            $consumer->total_points = 0;
            $consumer->total_stamps = '';
            //$consumer->user_id = Confide::user()->id;
            $consumer->consumer_id = $core->id;
            $consumer->consumer()->associate($core);
            $consumer->save();
        } catch (Exception $ex) {

        }

        return Redirect::route('lc.consumers.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$consumer = Model::find($id);

        return View::make('modules.lc.consumers.show')
            ->with('consumer', $consumer);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$consumer = Model::find($id);

        return View::make('modules.lc.consumers.edit')
            ->with('consumer', $consumer);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
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

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            $consumer = Model::find($id)->consumer;
            $consumer->first_name = Input::get('first_name');
            $consumer->last_name = Input::get('last_name');
            $consumer->email = Input::get('email');
            $consumer->phone = Input::get('phone');
            $consumer->address = Input::get('address');
            $consumer->postcode = Input::get('postcode');
            $consumer->city = Input::get('city');
            $consumer->country = Input::get('country');
            $consumer->save();

            return Redirect::route('lc.consumers.index');
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$consumer = Model::find($id);
        $consumer->delete();

        return Redirect::route('lc.consumers.index');
	}

    public function appIndex() {
        return View::make('modules.lc.app.index');
    }
}
