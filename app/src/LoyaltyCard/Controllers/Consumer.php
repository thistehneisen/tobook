<?php namespace App\LoyaltyCard\Controllers;

use Input, Session, Redirect, View, Validator, Request, Response;
use Confide;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\Core\Controllers\Base as Base;

class Consumer extends Base
{
    /**
     * Make view index for both app and BE
     * @return Response
     */
    private function viewIndex($isApp = false) {
        $consumers = Model::paginate(10);

        $viewName = $isApp ? 'modules.lc.app.index' : 'modules.lc.consumers.index';
        return View::make($viewName)
            ->with('consumers', $consumers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return $this->viewIndex();
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
            'email'         => 'required|email|unique:consumers',
            'phone'         => 'required|numeric',
            'address'       => 'required',
            'postcode'      => 'required|numeric',
            'city'          => 'required',
            'country'       => 'required',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            if (Request::ajax()) {
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors()->toArray(),
                ]);
            }

            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'first_name'    => Input::get('first_name'),
                'last_name'     => Input::get('last_name'),
                'email'         => Input::get('email'),
                'phone'         => Input::get('phone'),
                'address'       => Input::get('address'),
                'postcode'      => Input::get('postcode'),
                'city'          => Input::get('city'),
                'country'       => Input::get('country'),
            ];

            // Create core consumer first
            $core = Core::make($data, Confide::user()->id);

            // Then create a LC consumer
            $consumer = new Model;
            $consumer->total_points = 0;
            $consumer->total_stamps = '';
            $consumer->consumer_id = $core->id;
            $consumer->consumer()->associate($core);
            $consumer->save();
        } catch (Exception $ex) {

        }

        if (Request::ajax()) {
            return Response::json([
                'success' => true,
                'message' => 'Customer created successfully!',
            ]);
        }

        return Redirect::route('lc.consumers.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $consumer = Model::find($id);

        if (Request::ajax()) {
            // return json_encode($consumer);
            $vouchers = VoucherModel::where('user_id', Confide::user()->id)->get();
            $offers = OfferModel::where('user_id', Confide::user()->id)->get();

            $data = [
                'consumer' => $consumer,
                'offers' => $offers,
                'vouchers' => $vouchers,
            ];

            return View::make('modules.lc.app.show', $data);
        }

        // return View::make('modules.lc.consumers.show')
        //             ->with('consumer', $consumer);
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
        $core = Core::find($id);
        $core->hide(Confide::user()->id);
        $consumer = Model::find($id);
        $consumer->delete();

        if (Request::ajax()) {
            Response::json([
                'success' => true,
            ]);
        }

        return Redirect::route('lc.consumers.index');
    }

    /**
     * Index for app
     * @return Response
     */
    public function appIndex() {
        return $this->viewIndex(true);
    }
}
