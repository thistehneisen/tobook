<?php
namespace App\FlashDeal\Controllers;

use Input, Session, Redirect, View, Validator;
use \App\FlashDeal\Models\Service as ServiceModel;
use \App\FlashDeal\Models\Coupon as CouponModel;
use Confide;

class Coupon extends \App\Core\Controllers\Base {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the coupons
        $coupons = CouponModel::where('user_id', '=', Confide::user()->id)
                        ->get();
        
        // load the view and pass the coupons
        return View::make('modules.fd.coupons.index')
            ->with('coupons', $coupons);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)
                    ->get();
        
        return View::make('modules.fd.coupons.create')
            ->with('services', $services);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'service_id'         => 'required',
            'discounted_price'   => 'required|numeric',
            'start_date'         => 'required|date_format:"Y-m-d"',
            'end_date'           => 'required|date_format:"Y-m-d"',
            'quantity'           => 'required|numeric',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $coupon = new CouponModel;
            $coupon->service_id = Input::get('service_id');
            $coupon->discounted_price = Input::get('discounted_price');
            $coupon->start_date = Input::get('start_date');
            $coupon->end_date = Input::get('end_date');
            $coupon->quantity = Input::get('quantity');
            $coupon->user_id = Confide::user()->id;
            $coupon->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('fd.coupons.index');
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
        $coupon = CouponModel::find($id);
    
        return View::make('modules.fd.coupons.show')
            ->with('coupon', $coupon);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $coupon = CouponModel::find($id);
        
        // get all the services
        $services = ServiceModel::where('user_id', '=', Confide::user()->id)->get();
    
        return View::make('modules.fd.coupons.edit')
            ->with('coupon', $coupon)
            ->with('services', $services);
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
            'service_id'         => 'required',
            'discounted_price'   => 'required|numeric',
            'start_date'         => 'required|date_format:"Y-m-d"',
            'end_date'           => 'required|date_format:"Y-m-d"',
            'quantity'           => 'required|numeric',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $coupon = CouponModel::find($id);
            $coupon->service_id = Input::get('service_id');
            $coupon->discounted_price = Input::get('discounted_price');
            $coupon->start_date = Input::get('start_date');
            $coupon->end_date = Input::get('end_date');
            $coupon->quantity = Input::get('quantity');
            $coupon->user_id = Confide::user()->id;
            $coupon->save();
            
            return Redirect::route('fd.coupons.index')
                ->with('message', 'Successfully updated!');
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
        $coupon = CouponModel::find($id);
        $coupon->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('fd.coupons.index');
    }
    

}
