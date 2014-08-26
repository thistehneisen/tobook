<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use \App\LoyaltyCard\Models\Offer as OfferModel;
use App\Core\Controllers\Base as Base;
use Auth;

class Offer extends Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $offers = OfferModel::where('user_id', Auth::user()->id)->get();

        return Response::json([
            'error' => false,
            'offers' => $offers->toArray(),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $inserted_id = 0;
        $rules = [
            'name'          => 'required',
            'required'      => 'required|numeric',
            'free'          => 'required|numeric',
            'active'        => 'required|numeric',
            'auto_add'      => 'required|numeric',
        ];

        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => 'Invalid data'
            ], 400);
        } else {
            $offer = new OfferModel;
            $offer->name = Request::get('name');
            $offer->user_id = Auth::user()->id;
            $offer->required = Request::get('required');
            $offer->free_service = Request::get('free');
            $offer->total_used = 0;
            $offer->is_active = Request::get('active');
            $offer->is_auto_add = Request::get('auto_add');
            $offer->save();
            $inserted_id = $offer->id;

            return Response::json([
                'error' => false,
                'created' => $inserted_id,
                'message' => 'Offer created',
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
        $offer = OfferModel::find($id);

        return Response::json([
            'error' => false,
            'offers' => $offer->toArray(),
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
        $offer = OfferModel::find($id);

        foreach ([
            'name'      => 'name',
            'required'  => 'required',
            'free'      => 'free_service',
            'active'    => 'is_active',
            'auto_add'  => 'is_auto_add',
        ] as $key => $value) {
            if (Request::get($key)) {
                $offer->$value = Request::get($key);
            }
        }

        $offer->save();

        return Response::json([
            'error' => false,
            'message' => 'Offer updated',
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
        $offer = OfferModel::find($id);
        $offer->delete();

        return Response::json([
            'error' => false,
            'message' => 'Offer deleted',
        ], 204);
    }
}
