<?php namespace App\LoyaltyCard\Controllers;
use Input, Session, Redirect, View, Validator;
use \App\LoyaltyCard\Models\Offer as OfferModel;
use Confide;

class Offer extends \App\Core\Controllers\Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the offers
        $offers = OfferModel::paginate(10);

        // load the view and pass the offers
        return View::make('modules.lc.offers.index')
            ->with('offers', $offers);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.lc.offers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name'          => 'required',
            'required'      => 'required|numeric',
            'free'          => 'required|numeric',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $offer = new OfferModel;
            $offer->name = Input::get('name');
            $offer->user_id = Confide::user()->id;
            $offer->required = Input::get('required');
            $offer->free_service = Input::get('free');
            $offer->total_used = 0;
            $offer->is_active = Input::get('active');
            $offer->is_auto_add = Input::get('auto_add');
            $offer->save();

            return Redirect::route('lc.offers.index');
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
        $offer = OfferModel::find($id);

        return View::make('modules.lc.offers.show')
            ->with('offer', $offer);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $offer = OfferModel::find($id);

        return View::make('modules.lc.offers.edit')
            ->with('offer', $offer);
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
            'name'          => 'required',
            'required'      => 'required|numeric',
            'free_service'  => 'required|numeric',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
                return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $offer = OfferModel::find($id);
            $offer->name = Input::get('name');
            $offer->required = Input::get('required');
            $offer->free_service = Input::get('free_service');
            $offer->is_active = Input::get('is_active');
            $offer->is_auto_add = Input::get('is_auto_add');
            $offer->save();

            return Redirect::route('lc.offers.index');
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
        $offer = OfferModel::find($id);
        $offer->delete();

        return Redirect::route('lc.offers.index');
    }
}
