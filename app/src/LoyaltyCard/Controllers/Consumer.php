<?php namespace App\LoyaltyCard\Controllers;
use Input, Session, Redirect, View, Validator;
use \App\LoyaltyCard\Models\Consumer as ConsumerModel;
use Confide;

class Consumer extends \App\Controllers\Base {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// get all the consumers
        $consumers = ConsumerModel::all();

        // load the view and pass the consumers
        return View::make('loyalty.consumers.index')->with('consumers', $consumers);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('loyalty.consumers.create');
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
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('consumers/create')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            $consumer = new ConsumerModel;
            $consumer->first_name = Input::get('first_name');
            $consumer->last_name = Input::get('last_name');
            $consumer->owner = Input::get('owner_id');
            $consumer->email = Input::get('email');
            $consumer->phone = Input::get('phone');
            $consumer->address = Input::get('address');
            $consumer->city = Input::get('city');
            $consumer->score = Input::get('score');
            $consumer->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::to('consumers');
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
		$consumer = ConsumerModel::find($id);

        return View::make('loyalty.consumers.show')->with('consumer', $consumer);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$consumer = ConsumerModel::find($id);

        return View::make('loyalty.consumers.edit')->with('consumer', $consumer);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('customers/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            $consumer = ConsumerModel::find($id);
            $consumer->first_name = Input::get('first_name');
            $consumer->last_name = Input::get('last_name');
            $consumer->email = Input::get('email');
            $consumer->save();

            Session::flash('message', 'Successfully updated!');
            return Redirect::to('consumers');
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
		$consumer = ConsumerModel::find($id);
        $consumer->delete();

        Session::flash('message', 'Successfully deleted!');
        return Redirect::to('consumers');
	}


}
